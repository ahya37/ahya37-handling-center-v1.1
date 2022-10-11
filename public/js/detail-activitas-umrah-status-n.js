const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);
const aktivitasId = $('#aktivitasId').val();

// TAMPIL DATA
$(function () {
  $("#listData").DataTable({
    processing: true,
    pageLength: 200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: `/aktivitas/detailactivitas/statusN/${aktivitasId}/${id}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
      { data: "pelaksanaan", name: "pelaksanaan" },
      { data: "alasan", name: "alasan" },
      { data: "updatedAt", name: "updatedAt" },
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
