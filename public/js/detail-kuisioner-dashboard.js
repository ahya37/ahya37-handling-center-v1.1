const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);
let urlTourcode = "/api/getdataumrah";


const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
let tourcode = "";
let month = "";
let year = "";



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
initialSelectTorucode(urlTourcode, id);

const table = $("#tablePlace").DataTable({
  pageLength: 100,
  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[2, "desc"]],
  autoWidth: false,
  ajax: {
    url: `/api/kuisioner/dashboard/detail/listdata/${id}`,
    type: "POST",
    data: function (q) {
      q._token = CSRF_TOKEN,
      q.tourcode = tourcode;
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.pertanyaan}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        return row.tourcode;
      },
    },
    {
      targets: 2,
      sortable: true,
      render: function (data, type, row, meta) {
        return `<p class="text-right">${row.jml_jawaban}</p>`;
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        return row.kategori;
      },
    },
  ],
});

