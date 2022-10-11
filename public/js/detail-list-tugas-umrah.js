const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);

// TAMPIL DATA
$(function () {
  $("#listData").DataTable({
    processing: true,
    pageLength:200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: `/aktivitas/jadwal/umrah/detail/validasi/${id}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
      { data: "pelaksanaan", name: "pelaksanaan" }
    ],
    order: [[1, "asc"]],
    columnDefs: [
      {
        targets: [0],
        visible: false,
      },
    ],
  });
});

// VALIDASI
function onValidate(data) {
  const id = data.id;
  const nomor = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  const userId = $("#author").val();

  Swal.fire({
    title: `Yakin validasi nomor : ${nomor}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/aktivitas/jadwal/umrah/detail/validasi/save/${id}`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          cby: userId,
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
            icon: "danger",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
      });
    }
    const table = $("#listData").DataTable();
    table.ajax.reload();
  });
}

function onDetaiSatusNo(data){
  Swal.fire(data.value)

}