let urlTourcode = "/api/getdataumrah";

const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
let tourcode = "";
let month = "";
let year = "";

$(".datepicker").datepicker(
  {
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
  },
  ($.fn.datepicker.dates["en"] = {
    days: [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Augustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ],
    monthsShort: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "Mei",
      "Jun",
      "Jul",
      "Agu",
      "Sep",
      "Okt",
      "Nov",
      "Des",
    ],
    today: "Today",
    clear: "Clear",
    format: "MM",
    titleFormat: "MM" /* Leverages same syntax as 'format' */,
    weekStart: 0,
  })
);

$("#dates").on("changeDate", async function (selected) {
  const monthSelected = selected.date.getMonth() + 1;
  const yearSelected = selected.date.getFullYear();
  month = monthSelected;
  year = yearSelected;
  urlTourcode = `/api/getdataumrahbymonth/${month}/${year}`;
  initialSelectTorucode(urlTourcode);
  table.ajax.reload(null, false);
});

$(".tourcode").on("change", function () {
  tourcode = $("select[name=tourcode] option").filter(":selected").val();
  table.ajax.reload(null, false);
});

async function allMonth() {
  $("#dates").val("");
  month = "";
  year = "";
  tourcode = "";
  table.ajax.reload(null, false);
}

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
initialSelectTorucode(urlTourcode);

const table = $("#tablePlace").DataTable({
  pageLength: 100,
  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[0, "desc"]],
  autoWidth: false,
  ajax: {
    url: "/umrah/listdata",
    type: "POST",
    data: function (q) {
      (q._token = CSRF_TOKEN), (q.tourcode = tourcode);
      q.month = month;
      q.year = year;
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.tourcode}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        return `<p>${row.dates}</p>`;
      },
    },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        return `<p align="right">${row.count_jamaah}</p>`;
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        return `
                <a href="/umrah/edit/${row.id}" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a>
                <button onclick="onDelete(this)" id="${row.id}" value="${row.tourcode}" title="Hapus" class="fa fa-trash text-danger"></button>
              `;
      },
    },
  ],
});

// delete
function onDelete(data) {
  const id = data.id;
  const tourcode = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus tourcode : ${tourcode}?`,
    text: "Data yang dihapus tidak dapat dikembalikan",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/umrah/destroy`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
        error: function (error) {
          Swal.fire({
            position: "center",
            icon: "warning",
            title: `Gagal, ada pembimbing yang masih aktif mengerjakan`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
    const table = $("#tablePlace").DataTable();
    table.ajax.reload();
  });
}


