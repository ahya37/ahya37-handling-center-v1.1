let urlTourcode = "/api/getdataumrah";
let urlGrafik = "/api/grafik/tugas";

let month = "";
let year = "";

// INIT DATEPICKER
$(".datepicker").datepicker({
  format: "MM",
  viewMode: "months",
  minViewMode: "months",
  autoclose: true,
  language: "id"
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

$(".tourcode").on("change", function () {
  tourcode = $("select[name=tourcode] option").filter(":selected").val();
  // initializeGrafik(urlGrafik, month, year, tourcode);
});

async function allMonth() {
  $('#dates').val("");
  month = "";
  year = "";
  tourcode = "";
  urlTourcode = "/api/getdataumrah";
  initialSelectTorucode(urlTourcode);
  initializeGrafik(urlGrafik, month, year, tourcode);
}

function onSubmit(){
  initializeGrafik(urlGrafik, month, year, tourcode);
}

let divTugas = $("#frame");
let divLoad = $("#load");

// GET GRAFIK TOURCODE DEFAULT
function initializeGrafik(urlGrafik, month, year, tourcode) {
  // AJAX CARD TOURCODE
  $.ajax({
    url: urlGrafik,
    method: "POST",
    dataType: "json",
    cache: false,
    data: { month: month, year: year, tourcode: tourcode },
    beforeSend: function () {
      divLoad.append(
        `<div class="text-center">
            <div class="spinner-border text-warning" role="status">
              <span class="visually-hidden"></span>
            </div>
          </div>`
      );
    },
    success: function (data) {
      divTugas.empty();
      let dataTourcode = "";
      data.umrah.forEach((m) => {
        dataTourcode = `<div class="card">
                          <div class="card-body">
                            <div class="col-md-12">
                            <div id="tugas${m.id}"></div>
                            </div>
                          </div>
                        </div>`;
        divTugas.append(dataTourcode);
        callAjaxChartByTourcode(m.id, m.tourcode, m.pembimbing);
      });
    },
    complete: function () {
      divLoad.empty();
    },
  });
}

// CALL FUNCTION CHART
function callAjaxChartByTourcode(id, tourcodes, pembimbing) {
  $.ajax({
    url: "/api/chart/grafik/tugas",
    method: "GET",
    dataType: "json",
    cache: false,
    method: "POST",
    data: { id: id },
    beforeSend: function () {
      divLoad.append("Loading..");
    },
    success: function (data) {
      Highcharts.chart(`tugas${id}`, {
        chart: {
          type: "bar",
          styledMode: true,
        },
        credits: {
          enabled: false,
        },
        title: {
          text: `Grafik Tugas Pembimbing Umrah <br> ${tourcodes} - ${pembimbing}`,
        },
        xAxis: {
          categories: data.judul,
        },
        yAxis: {
          min: 0,
          title: {
            text: "Pelaksanaan",
          },
        },
        legend: {
          reversed: true,
        },
        plotOptions: {
          series: {
            stacking: "normal",
          },
        },
        series: [
          {
            name: "Ya",
            data: data.data_Y,
            cursor: "pointer",
            point: {
              events: {
                click: function (event) {
                  
                  window.location.assign(this.url);
                },
              },
            },
          },
          {
            name: "Tidak",
            data: data.data_N,
            point: {
              events: {
                click: function (event) {
                  
                  window.location.assign(this.url);
                },
              },
            },
          },
          {
            name: "Belum",
            data: data.data_null,
            point: {
              events: {
                click: function (event) {
                  
                  window.location.assign(this.url);
                },
              },
            },
          },
        ],
      });
    },
    complete: function () {
      divLoad.empty();
    },
  });
}

$(".filter").on("changeDate", async function (selected) {
  $('#tourcode').empty();

  const monthSelected = selected.date.getMonth() + 1;
  const yearSelected = selected.date.getFullYear();
  month = monthSelected;
  year = yearSelected;
  (urlTourcode = `/api/getdataumrahbymonth/${month}/${year}`),
    initialSelectTorucode(urlTourcode);
  // initializeGrafik(urlGrafik, month, year);

  // GET DATA TOURCODE BERDASARKAN BULAN DAN TAHUN
});

// CALL FUNCTION SELECT TOURCODE
initialSelectTorucode(urlTourcode);
// CALL FUNCTION CARD GRAFIK
initializeGrafik(urlGrafik, month, year);
