// TAMPIL DATA
$(function () {
  let table = $("#listData").DataTable({
    processing: true,
    pageLength:100,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/kuisioner/kategori/pilihan/listdata",
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
    title: `Yakin hapus pilihan : ${name}?`,
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
        url: `/kuisioner/kategori/pilihan/delete`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          if (data.data.message === "Terhapus") {
            Swal.fire({
              position: "center",
              icon: "success",
              title: `${data.data.message}`,
              showConfirmButton: false,
              width: 500,
              timer: 900,
            });
            const table = $("#listData").DataTable();
            table.ajax.reload();
          } else {
            Swal.fire({
              position: "center",
              icon: "warning",
              title: `${data.data.message}`,
              showConfirmButton: false,
              width: 500,
              timer: 900,
            });
          }
        },
        error: function (error) {
          Swal.fire({
            position: "center",
            icon: "warning",
            title: `Gagal, Pilihan terpakai dipertanyaan`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
    const table = $("#listData").DataTable();
    table.ajax.reload();
  });
}


async function addKategoriPilihanJawaban() {
  const { value: text } = await Swal.fire({
    input: "textarea",
    inputLabel: "Buat pilihan jawaban",
    inputPlaceholder: "Isi pilihan jawaban disini...",
    inputAttributes: {
      "aria-label": "Type your message here",
    },
    showCancelButton: true,
    cancelButtonText: "Batal",
  });
  if (text) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: "/kuisioner/kategori/pilihan/save",
      method: "POST",
      cache: false,
      data: {
        isi: text,
        _token: CSRF_TOKEN,
      },
      success: function (data) {
        if (data.data.message === "Sukses") {
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
          const table = $("#listData").DataTable();
          table.ajax.reload();
        } else {
          Swal.fire({
            position: "center",
            icon: "warning",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        }
      },
    });
  } else {
    Swal.fire("Harap isi pilihan jawaban");
  }
}

async function editKategoriPilihanJawaban(data) {
  const { value: text } = await Swal.fire({
    input: "textarea",
    inputLabel: "Buat pilihan jawaban",
    inputPlaceholder: "Isi pilihan jawaban disini...",
    inputAttributes: {
      "aria-label": "Type your message here",
    },
    showCancelButton: true,
    cancelButtonText: "Batal",
  });
  if (text) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: "/kuisioner/kategori/pilihan/update",
      method: "POST",
      cache: false,
      data: {
        id: data.id,
        isi: text,
        _token: CSRF_TOKEN,
      },
      success: function (data) {
        if (data.data.message === "Sukses") {
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
          const table = $("#listData").DataTable();
          table.ajax.reload();
        } else {
          Swal.fire({
            position: "center",
            icon: "warning",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        }
      },
    });
  } else {
    Swal.fire("Harap isi pilihan jawaban");
  }
}