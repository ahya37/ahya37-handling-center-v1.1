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
    url: "/item/listdata",
    type: "POST",
    data: function (q) {
      (q._token = CSRF_TOKEN)
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
        return `<img src="/storage/${row.image}" width="50px">`;
      },
    },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        return `<p>${row.name}</p>`;
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        return `<p>${row.stok}</p>`;
      },
    },
    {
      targets: 4,
      render: function (data, type, row, meta) {
        return `
                <a href="/umrah/edit/${row.id}" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a>
                <button onclick="onDelete(this)" id="${row.id}" value="${row.id}" title="Hapus" class="fa fa-trash btn text-danger"></button>
              `;
      },
    },
  ],
});


