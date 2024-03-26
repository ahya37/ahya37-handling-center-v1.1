const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);

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
      url: `/aktivitas/detailactivitas/${id}`,
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "pelaksanaan", name: "pelaksanaan" },
      { data: "nama", name: "nama" },
      { data: "alasan", name: "alasan" },
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

function cekAndPerbaruiTugas() {
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: `/aktivitas/cek/perbarui/tugas`,
    method: "POST",
    data: { id: id, _token: CSRF_TOKEN },
    beforeSend: function () {
      $(".cek").hide();
      $(".loading").show();
      $(".loading").append(
        `<div class="text-center">
              <button class="btn btn-primary" type="button" disabled>
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Cek Tugas ...
            </button>
          </div>`
      );
    },
    success: function (data) {
      const status = data.data.status;
      if (status === 1) {
        Swal.fire({
          position: "center",
          icon: "success",
          title: `${data.data.message}`,
          showConfirmButton: false,
          width: 500,
          timer: 900,
        });
        window.location.reload();
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
    complete: function () {
      $(".loading").hide();
      $(".loading").empty();
      $(".cek").show();
    },
  });
}

function validation(){
  let idTugas = [];
  $('input[name="validate[]"]:checked').each(function () {
    idTugas.push(this.value);
  });

  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: 'Validasi Tugas ?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya',
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/user/muthowwif/jadwal/umrah/active/updatevalidate`,
        method: "POST",
        data: { id: idTugas, _token: CSRF_TOKEN },
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
      });
  }
  })

  
}

async function NilaiPertimbangan(data){
  const id = data.getAttribute('data-id');

  const { value: nilai } = await Swal.fire({
    input: 'number',
    inputLabel: 'Masukan Nilai',
  })
  
  if (nilai) {
    // Swal.fire(`Nilai : ${nilai}`)
    // AJAX UPDATE NILAI AKHIR 
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      $.ajax({
        url: "/user/muthowwif/update/nilai/pertimbangan",
        method: "POST",
        cache: false,
        data: {
          id: id,
          nilai: nilai,
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
          window.location.reload();
        },
      });
  }else{
    Swal.fire(`Tidak ada `)
  }

}