$(function () {
  $("#tablePlace").DataTable({
    processing: true,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/pembimbing/listdata",
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nama", name: "nama" },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        width: "15%",
      },
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

// delete
function onDelete(data) {
  const id = data.id;
  const name = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus Pembimbing : ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/pembimbing/destroy`,
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
            title: `Gagal, Pembimbing masih aktif mengerjakan tugas`,
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

$(function () {
  $("#petugas").DataTable({
    processing: true,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/petugas/listdata",
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nama", name: "nama" },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        width: "15%",
      },
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

function onDeletePetugas(data) {
  const id = data.id;
  const name = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus Petugas : ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/petugas/destroy`,
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
            title: `Gagal, Petugas masih aktif mengerjakan tugas`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
    const table = $("#petugas").DataTable();
    table.ajax.reload();
  });
}