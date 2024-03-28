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
      url: `/aktivitas/muthowwif/detailactivitas/statusNull/${aktivitasId}/${id}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
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

function validate() {
  let idTugas = [];
  $('input[name="validate[]"]:checked').each(function () {
    idTugas.push(this.value);
  });
  
  // AJAX UNTUK VALIDASI
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  if (idTugas.length === 0) {
    Swal.fire({
      position: "center",
      icon: `warning`,
      title: `Silahkan centang SOP`,
      showConfirmButton: false,
      width: 500,
      timer: 900,
    });
  }else{
    Swal.fire({
      title: `Nilai ulang tugas ?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/aktivitas/muthowwif/jadwal/umrah/active/renilai`,
          method: "POST",
          data: { id: idTugas, aktivitasId: id, _token: CSRF_TOKEN },
          beforeSend: function () {
            $("#btnValidate").hide();
            $(".loading").show();
            $(".loading").append(
              `<div>
                <button class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Nilai ulang Tugas ...
              </button>
            </div>`
            );
          },
          success: function (data) {          
            Swal.fire({
              position: "center",
              icon: `success`,
              title: `${data.data.message}`,
              showConfirmButton: false,
              width: 500,
              timer: 900,
            });
            window.location.reload();
          },
          complete: function () {
            $("#btnValidate").show();
            $(".loading").hide();
            $(".loading").empty();
          },
        });
      }
    });
  }
}