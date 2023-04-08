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

// delete
function onDelete(data) {
  const id = data.id;
  const tourcode = data.value;
  
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


