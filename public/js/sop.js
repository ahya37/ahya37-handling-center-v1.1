$(function () {
  $("#tablePlace").DataTable({
    processing: true,
    pageLength: 200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/tugas/listdatasop",
    },
    columns: [
      { data: "id", name: "id" },
      { data: "name", name: "name" },
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

function onDelete(data) {
  const id = data.id;
  const name = data.getAttribute("data-name");

  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin Hapus SOP : ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/tugas/delete/sop`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "success",
            title: `${data.data.message}`,
          });

          const table = $("#tablePlace").DataTable();
          table.ajax.reload();
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
  });
}

async function onCopy(data) {
  const id = data.id;
  const name = data.getAttribute("data-name");

  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

  const { value: formValues } = await Swal.fire({
    title: "Copy SOP",
    html: `
      <input id="swal-input2" class="swal2-input" value="${name} Copy" placeholder="Nama SOP">
      `,
    focusConfirm: false,
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Simpan",
    timerProgressBar: true,
    preConfirm: () => {
      return [
        document.getElementById("swal-input2").value,
      ];
    },
  });

  if (formValues) {
    // ajax save judul
    $.ajax({
      url: "/tugas/sop/copy",
      method: "POST",
      cache: false,
      data: {
        id: id,
        name: formValues,
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
        },
        );
        const table = $("#tablePlace").DataTable();
        table.ajax.reload();
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
}

$(function () {
  $("#sopPetugas").DataTable({
    processing: true,
    pageLength: 200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/tugas/listdatasoppetugas",
    },
    columns: [
      { data: "id", name: "id" },
      { data: "name", name: "name" },
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