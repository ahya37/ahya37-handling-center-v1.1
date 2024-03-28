const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);

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
      url: `/umrah/tag/orange/listdetaildata/${id}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "no_urut", name: "no_urut" },
      { data: "foto", name: "foto" },
      { data: "nama_jamaah", name: "nama_jamaah" },
      { data: "telp_jamaah", name: "telp_jamaah" },
      { data: "email_jamaah", name: "email_jamaah" },
      { data: "alamat_jamaah", name: "alamat_jamaah" },
      { data: "room_jamaah", name: "room_jamaah" },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
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
  const name = data.value;

  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus : ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/umrah/tag/orange/jamaah/delete`,
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

          window.location.reload();
        },
        error: function (error) {
          Swal.fire({
            position: "center",
            icon: "danger",
            title: `Gagal hapus`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
      });
      // const table = $("#tablePlace").DataTable();
      // table.ajax.reload();

    }
  });
}

function openModal(data) {
  const id = data.id;
  $("#myModal").modal("show");
  $('#idJamaah').val(id);
}

function closeModal() {
  $("#myModal").modal("hide");
  $("#image").val("");
  $("#idJamaah").val("");
  $('#btnloading').hide();
  $('#btnsave').show();
  $('#btnclose').show();
  document.getElementById("image-preview").src = '';
  const table = $("#tablePlace").DataTable();
  table.ajax.reload();
}

function previewImage() {
  document.getElementById("image-preview").style.display = "block";
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("image").files[0]);

  oFReader.onload = function (oFREvent) {
    document.getElementById("image-preview").src = oFREvent.target.result;
  };
};


$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#form').submit(function(e) {
 e.preventDefault();
 let formData = new FormData(this);
 $.ajax({
    method:'POST',
    url: `/umrah/tag/orange/jamaah/uploadfoto`,
     data: formData,
     contentType: false,
     processData: false,
     success: (response) => {
       if (response) {
         this.reset();
         Swal.fire({
          position: "center",
          icon: "success",
          title: `${response.data.message}`,
          showConfirmButton: false,
          width: 500,
          timer: 900,
        });
         closeModal();
         const table = $("#tablePlace").DataTable();
         table.ajax.reload();
       }
     },
     error: function(response){
          Swal.fire({
            position: "center",
            icon: "danger",
            title: response.responseJSON.errors.file,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
     }
 });
});

$('#btnsave').on('click', function(){
  $('#btnsave').hide();
  $('#btnclose').hide();
  $('#btnloading').show();
});