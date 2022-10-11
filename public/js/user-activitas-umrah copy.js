// TAMPIL DATA
const query = document.URL;
const aktivitasUmrahId = query.substring(query.lastIndexOf("/") + 1);

$(function () {
  $("#listData").DataTable({
    processing: true,
    pageLength:200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    autoWidth: false,

    ajax: {
      url: `/user/listtugas/${aktivitasUmrahId}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
      { data: "check", name: "check" },
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

// ISI TUGAS TANPA FILE UPLOAD
async function selected(elem) {
  const status = elem.value;
  const id = elem.id;
  const query = document.URL;
  const aktivitasUmrahId = query.substring(query.lastIndexOf("/") + 1);

  if (status == "N") {
    const { value: text } = await Swal.fire({
      input: "textarea",
      inputLabel: "Harap isi alasan jika menjawab tidak",
      inputPlaceholder: "Isi alasan disini...",
      inputAttributes: {
        "aria-label": "Type your message here",
      },
      showCancelButton: true,
      cancelButtonText: "Batal",
    });
    if (text) {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      $.ajax({
        url: "/user/createTugas",
        method: "POST",
        cache: false,
        data: {
          id: id,
          status: status,
          alasan: text,
          aktivitasUmrahId: aktivitasUmrahId,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          // SWEAT ALERT
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
      });
      const table = $("#listData").DataTable();
      table.ajax.reload();
    } else {
      Swal.fire("Harap isi alasan");
      const table = $("#listData").DataTable();
      table.ajax.reload();
    }
  } else {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: "/user/createTugas",
      method: "POST",
      cache: false,
      data: {
        id: id,
        status: status,
        aktivitasUmrahId: aktivitasUmrahId,
        _token: CSRF_TOKEN,
      },
      success: function (data) {
        // SWEAT ALERT
        Swal.fire({
          position: "center",
          icon: "success",
          title: `${data.data.message}`,
          showConfirmButton: false,
          width: 500,
          timer: 900,
        });
      },
    });
    const table = $("#listData").DataTable();
    table.ajax.reload();
  }
}

// ISI TUGAS DENGAN FILE UPLOAD
async function selectedWithFile(elem) {
  const status = elem.value;
  const id = elem.id;
  const query = document.URL;
  const aktivitasUmrahId = query.substring(query.lastIndexOf("/") + 1);

  if (status == "N") {
    const { value: text } = await Swal.fire({
      input: "textarea",
      inputLabel: "Harap isi alasan jika menjawab tidak",
      inputPlaceholder: "Isi alasan disini...",
      inputAttributes: {
        "aria-label": "Type your message here",
      },
      showCancelButton: true,
      cancelButtonText: "Batal",
    });
    if (text) {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      $.ajax({
        url: "/user/createTugaswithfile",
        method: "POST",
        cache: false,
        data: {
          id: id,
          status: status,
          alasan: text,
          aktivitasUmrahId: aktivitasUmrahId,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          // SWEAT ALERT
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
      });
      const table = $("#listData").DataTable();
      table.ajax.reload();
    } else {
      Swal.fire("Harap isi alasan");
      const table = $("#listData").DataTable();
      table.ajax.reload();
    }
  } else {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const { value: file } = await Swal.fire({
      title: 'Upload Foto Tugas / Kegiatan',
      input: 'file',
      inputAttributes: {
        'accept': 'image/*',
        'aria-label': 'Upload your profile picture'
      }
    })
    
    if (file) {
      const reader = new FileReader()
      reader.onload = (e) => {
      let fileType = file.type.split("/");
      if(fileType[0] === "application"){
        Swal.fire({
          icon: 'warning',
          title: 'Gagal, Yang Anda upload bukan gambar',
        });
      }else{
          $.ajax({
            headers: CSRF_TOKEN,
            url: "/user/createTugaswithfile",
            method: "POST",
            cache: false,
            data: {
              id: id,
              status: status,
              image: e.target.result,
              aktivitasUmrahId: aktivitasUmrahId,
              _token: CSRF_TOKEN,
            },
            success: function (data) {
              // SWEAT ALERT
              Swal.fire({
                position: "center",
                icon: "success",
                title: `${data.data.message}`,
                showConfirmButton: false,
                width: 500,
                timer: 900,
              });
            },
          });
        }
      }
      reader.readAsDataURL(file)
    }
    const table = $("#listData").DataTable();
    table.ajax.reload();
  }
}
