// TAMPIL DATA
const query = document.URL;
const aktivitasUmrahId = query.substring(query.lastIndexOf("/") + 1);
const sop = $('#sopId').val();
console.log(sop)

$(function () {
  $("#listData").DataTable({
    processing: true,
    pageLength: 200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    autoWidth: false,

    ajax: {
      url: `/user/muthowwif/listtugas/${sop}/${aktivitasUmrahId}`,
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

$('#btnsave').on('click', function(){
  $('#btnsave').hide();
  $('#btnclose').hide();
  $('#btnloading').show();

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
        url: "/user/muthowwif/createTugas",
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
      url: "/user/muthowwif/createTugas",
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
  $("#image").val("");
  $("#note").val("");
  const requireImage = elem.getAttribute("data-require-image");
  if (requireImage === "Y") {
    $("#notif").text("Diharuskan upload foto");
    $("#notif").show();
	$("#mandatory1").show();
	$("#mandatory2").show();
  } else {
    $("#notif").empty();
	$("#notif").hide();
	
    // $("#mandatory1").empty();
    $("#mandatory1").hide();
	
	// $("#mandatory2").empty();
    $("#mandatory2").hide();
    
  }
  const status = elem.value;
  const id = elem.id;
  const userId = $("#userId").val();
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
        url: "/user/muthowwif/createTugaswithfile", 
        method: "POST",
        cache: false,
        data: {
          id: id,
          status: status,
          alasan: text,
          user_id: userId,
          aktivitasUmrahId: aktivitasUmrahId,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          // SWEAT ALERT
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
          const table = $("#listData").DataTable();
          table.ajax.reload();
          window.location.reload();
        },
      });
    } else {
      Swal.fire("Harap isi alasan");
      const table = $("#listData").DataTable();
      table.ajax.reload();
    }
  } else {
    // const requireImage = elem.getAttribute("data-require-image");
    // if (requireImage === "Y") {
    //   $("#image").attr("required", true);
    // } else {
    //   $("#image").removeAttr("required");
    // }

    $("#myModal").modal("show");
    const id = elem.id;
    $("#idTugas").val(id);
    const table = $("#listData").DataTable();
    table.ajax.reload();
  }
}

function closeModal() {
  $("#myModal").modal("hide");
  $("#image").val("");
  $("#note").val("");
  $("#notif").hide();
  const table = $("#listData").DataTable();
  table.ajax.reload();
}
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
function submitModal() {
  const allowExtension = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
  let CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

  let files = $("#image")[0].files;

  let id_tugas = $("#idTugas").val();
  let image = $("#image").val();
  let note = $("#note").val();

  let fileImage = files[0];

  if (image === "") {
    Swal.fire({
      position: "center",
      icon: "warning",
      title: `Foto tidak boleh kosong`,
      showConfirmButton: false,
      width: 500,
      timer: 900,
    });
  } else {
    if (!allowExtension.exec(image)) {
      image.value = "";
      Swal.fire({
        position: "center",
        icon: "warning",
        title: `Anda mengupload bukan gambar`,
        showConfirmButton: false,
        width: 500,
        timer: 900,
      });
      return false;
    }

    // INSERT
    $.ajax({
      url: "/user/muthowwif/createTugaswithfile",
      method: "POST",
      cache: false,
      processData: false, // required
      contentType: false, // required
      data: {
        id: id_tugas,
        status: "Y",
        alasan: note,
        image: fileImage,
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
        $("#myModal").modal("hide");
        $("#image").val("");
        $("#note").val("");
        const table = $("#listData").DataTable();
        table.ajax.reload();
      },
    });
  }
}
