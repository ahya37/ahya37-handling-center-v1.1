$(".datepicker").datepicker({
  format: "MM",
  viewMode: "months",
  minViewMode: "months",
  autoClose: true,
});

let month = "";
let year = "";
let tourcode = "";

const table = $("#data").DataTable({
  pageLength: 10,

  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[1, "desc"]],
  autoWidth: false,
  ajax: {
    url: "/api/umrah/dt/umrah/tourcode",
    type: "POST",
    data: function (q) {
      q.tourcode = tourcode;
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
        return `<a href='/aktivitas/active/${row.id}'><i class="fa fa-eye"></i></a>`;
      },
    },
  ],
});

$(".filter").on("changeDate", async function (selected) {
  const monthSelected = selected.date.getMonth() + 1;
  const yearSelected = selected.date.getFullYear();
  month = monthSelected;
  year = yearSelected;
  table.ajax.reload(null, false);
});

async function allMonth() {
  month = "";
  year = "";
  table.ajax.reload(null, false);
}
