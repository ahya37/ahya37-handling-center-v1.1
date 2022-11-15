$("#exampleFullScreenModal").on("show.bs.modal", function (event) {
  let button = $(event.relatedTarget);
  let recipient = button.data("whatever");
  let location = button.data("lokasi");
  let id = button.data("id");
  let modal = $(this);
  modal.find(".modal-title").text("Edit " + recipient);
  modal.find(".modal-body input[name='id']").val(id);
  modal.find(".modal-body input[name='name']").val(recipient);
  modal.find(".modal-body input[name='lokasi']").val(location);
});

// delete
function onDelete(data) {
  const id = data.id;
  const nomor = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus pertanyaan ${nomor}?`,
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
        url: `/kuisioner/delete`,
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
            icon: "warning",
            title: `Gagal`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
  });
}

function onUpdatePertanyaan(element) {
  const id = element.id;

  const judul = element.getAttribute("data-name");
  const nomor = element.value;
  modalEditPertanyaan(nomor, judul, id);
}

async function modalEditPertanyaan(nomor, judul, id) {
  const { value: formValues } = await Swal.fire({
    title: `Edit pertanyaan no : ${nomor}`,
    html: `
        <input  id="swal-input1" placeholder="Label" value="${nomor}"  class="swal2-input">
          <input id="swal-input2" value="${judul}" class="swal2-input" placeholder="Judul">`,
    focusConfirm: false,
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Simpan",
    timerProgressBar: true,
    preConfirm: () => {
      return [
        document.getElementById("swal-input1").value,
        document.getElementById("swal-input2").value,
      ];
    },
  });

  if (formValues) {
    // ajax edit judul
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
      url: "/kuisioner/pilihan/update",
      method: "POST",
      data: {
        id: id,
        nomor: formValues[0],
        isi: formValues[1],
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
    });
  }
}

// ADD PILIHAN JAWABAN
function selectOptionPilihan(elem) {
  const id = elem.getAttribute("data-idkuisioner");
  // AJAX CALL KATEGORI PILIHAN JAWABAN
  $.ajax({
    url: "/kuisioner/kategori/pilihan/jawaban",
    method: "GET",
    dataType: "json",
    success: async function (data) {
      const Pilihan = data.data.data;
      
      const { value: selected } = await Swal.fire({
        title: "Pilih Jawaban Pertanyaan",
        input: "select",
        inputOptions: { Pilihan },
        inputPlaceholder: "-Pilih Jawaban-",
        showCancelButton: true,
        inputValidator: (value) => {
          return new Promise((resolve) => {
            resolve();
          });
        },
      });

      if (selected) {
        // AJAX SAVE ADD PILIHAN JAWABAN
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
          url: "/kuisioner/ajax/pertanyaan/save",
          method: "POST",
          cache: false,
          data: {
            id: id,
            pilihan: selected,
            _token: CSRF_TOKEN,
          },
          success: function (data) {
            // SWEAT ALERT
            const responData = data.data.data;

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
              icon: responData == 0 ? "warning" : "success",
              title: `${data.data.message}`,
            });

            if (responData > 0) {
              window.location.reload();
            }
          },
        });
      } else {
        // Swal.fire(`Pilih Jumlah`);
      }
    },
  });
}

function onDeletePilihan(data) {
  const id = data.id;
  const nomor = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus pilihan no ${nomor}?`,
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
        url: `/kuisioner/pilihanjawaban/delete`,
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

          window.location.reload();
        },
        error: function (error) {
          Swal.fire({
            position: "center",
            icon: "warning",
            title: `Gagal`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
  });
}
