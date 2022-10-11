// TAMPIL DATA
const query = document.URL;
const aktivitasUmrahId = query.substring(query.lastIndexOf("/") + 1);

$(function () {
  $("#listData").DataTable({
    processing: true,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    autoWidth: false,

    ajax: {
      url: `/user/history/listtugas/${aktivitasUmrahId}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
      { data: "check", name: "check" },
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
