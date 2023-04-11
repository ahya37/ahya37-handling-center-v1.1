let item = '';
let statuses = '';
const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
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
    url: "/item/listdatahistory",
    type: "POST",
    data: function (q) {
      (q._token = CSRF_TOKEN, q.item = item, q.status = statuses)
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.no}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        return `<p>${row.name}</p>`;
      },
    },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        return `<p>${row.qty}</p>`;
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        return `<p>${row.first}</p>`;
      },
    },
    {
      targets: 4,
      render: function (data, type, row, meta) {
        return `<p>${row.last}</p>`;
      },
    },
    {
      targets: 5,
      render: function (data, type, row, meta) {
        return `<p>${row.status}</p>`;
      },
    },
    {
      targets: 6,
      render: function (data, type, row, meta) {
        return `<p>${row.created_at}</p>`;
      },
    },
  ]
});


$(".item").on("change", function () {
  item = $("select[name=item] option").filter(":selected").val();
  table.ajax.reload(null, false);
});

$(".status").on("change", function () {
  statuses = $("select[name=status] option").filter(":selected").val();
  table.ajax.reload(null, false);
});

$("#status").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-50")
      ? "50%"
      : "style",
  allowClear: Boolean($(this).data("allow-clear")),
});

$(".item").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-50")
      ? "50%"
      : "style",
  allowClear: Boolean($(this).data("allow-clear")),
});

function allData() {
  item = "";
  statuses = "";
  table.ajax.reload(null, false);

}