let urlTourcode = "/api/getdataumrah";
let urlGrafik = "/api/grafik/kuisioner";
let urlPembimbing = "/api/getdatapembimbing";

// INIT DATEPICKER
$(".datepicker").datepicker({
  format: "MM",
  viewMode: "months",
  minViewMode: "months",
  autoclose: true,
},
$.fn.datepicker.dates['en'] = {
  days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
  daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
  daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
  months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "Desember"],
  monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
  today: "Today",
  clear: "Clear",
  format: "MM",
  titleFormat: "MM", /* Leverages same syntax as 'format' */
  weekStart: 0
});

let from       = $('#from');
let to         = $('#to');
let start = "";
let end   = "";
let pembimbingId = ""; 
let tourcode = "";
let dateRange = "";
let divLoad = $("#load");

// GET VALUE MASING2 ID NYA
from.on('changeDate', async function(selected){
   let  startMonth = selected.date.getMonth() + 1;
   let startYear  = selected.date.getFullYear();
   start      = `${startYear}-${startMonth}-01`;
});

to.on('changeDate', async function(selected){
   let endMonth = selected.date.getMonth() + 1;
   let endYear  = selected.date.getFullYear();
   end      = `${endYear}-${endMonth}-31`;
   dateRange = `${start}/${end}`;
  //  initializeGrafik(dateRange, tourcode, pembimbingId);
});

// FUNGSI SELECT2
function initialSelectTorucode(urlTourcode) {
  $(".tourcode").select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
      ? "100%"
      : "style",
    placeholder: "Pilih Tourcode",
    allowClear: Boolean($(this).data("allow-clear")),
    ajax: {
      dataType: "json",
      url: urlTourcode,
      delay: 250,
      processResults: function (data) {

        return {
          results: $.map(data, function (item) {
            return {
              text: item.tourcode,
              id: item.tourcode,
            };
          }),
        };
      },
    },
  });
}

$(".pembimbing").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: "Pilih Pembimbing",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: urlPembimbing,
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data, function (item) {
          return {
            text: item.nama,
            id: item.id,
          };
        }),
      };
    },
  },
});

$(".pembimbing").on("change", function () {
  pembimbingId = $("select[name=pembimbing] option").filter(":selected").val();
  // initializeGrafik(dateRange, tourcode, pembimbingId);
  // GET TOURCODE BERDASARKAN PEMBIMBING TERPILIH
  urlTourcode = `/api/getdataumrah/${pembimbingId}`;
  initialSelectTorucode(urlTourcode);
});

$(".tourcode").on("change", function () {
  tourcode = $("select[name=tourcode] option").filter(":selected").val();
  // initializeGrafik(dateRange, tourcode, pembimbingId);
});

async function clearAllForm() {
  from.val("");
  to.val("");
  dateRange = "";
  tourcode = "";
  pembimbingId = "";
  urlTourcode = "/api/getdataumrah";
  $("#pembimbing").empty();
  $("#tourcode").empty();
  initialSelectTorucode(urlTourcode);
  initializeGrafik(dateRange,tourcode, pembimbingId);
}


// GET GRAFIK TOURCODE DEFAULT
function initializeGrafik(dateRange,tourcode, pembimbingId) {
  $.ajax({
    url: urlGrafik,
    method:"POST",
    cache: false,
    data: {
      daterange: dateRange,
      tourcode: tourcode,
      pembimbing_id : pembimbingId
    },
    beforeSend: function () {
      divLoad.append(
        `<div class="text-center">
            <div class="spinner-border text-warning" role="status">
              <span class="visually-hidden"></span>
            </div>
          </div>`
      );
    },
    success: function(data){
      const kuisioner = data.data.data;
      const nilai = data.data.nilai;
      if(kuisioner.length === 0){
        $('#container').empty();
        $('#container').append(
          `<div class="card">
            <div class="card-body"><p class="text-center">Tidak ada data</p></div>
          </div>`
          );
      }else{
        Highcharts.chart('container', {
          chart: {
              type: 'column'
          },
          title: {
              text: 'Grafik Kuisioner'
          },
          xAxis: {
              type: 'category',
              categories: kuisioner,
              crosshair: true,
              allowDecimals: false,
              labels: {
                  rotation: -45,
                  style: {
                      fontSize: '13px',
                      fontFamily: 'Verdana, sans-serif'
                  }
              }
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Skor'
              }
          },
          legend: {
              enabled: false
          },
          tooltip: {
              pointFormat: 'Skor: <b>{point.y}</b>'
          },
          plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: 0,
            },
            series: {
                stacking: "normal",
                borderRadius: 3,
                cursor: "pointer",
                point: {
                    events: {
                        click: function (event) {
                            // console.log(this.url);
                            window.location.assign(this.url);
                        },
                    },
                },
            },
        },
          series: [{
              name: 'Population',
              data: nilai,
              dataLabels: {
                  enabled: true,
                  rotation: -90,
                  color: '#FFFFFF',
                  align: 'left',
                  format: '{point.y}', // one decimal
                  y: 5, // 10 pixels down from the top
                  style: {
                      fontSize: '10px',
                      fontFamily: 'Verdana, sans-serif'
                  }
              }
          }]
        });

      }
  
    },
    complete: function () {
      divLoad.empty();
    }
  });
}
// CALL FUNCTION SELECT TOURCODE
initialSelectTorucode(urlTourcode);
initializeGrafik(dateRange, tourcode, pembimbingId);

function onSubmit(){
  initializeGrafik(dateRange, tourcode, pembimbingId);
}