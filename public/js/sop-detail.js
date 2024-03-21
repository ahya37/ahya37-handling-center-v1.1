$(document).ready(function () {
  $("table.display").DataTable();
});

const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
const userId = $("#userId").val();

function onUpdateSOP(element) {
  const status = element.getAttribute("data-status");
  const sopName = element.getAttribute("data-name");
  const id = element.getAttribute("data-id");
  if (status === "edit") {
    modalEditSop(sopName, id);
  } else {
    modalAddJudul(id);
  }
}

async function modalEditSop(sopName, id) {
  const { value: sop } = await Swal.fire({
    title: "Edit Nama SOP",
    input: "text",
    showCancelButton: true,
    cancelButtonText: "Batal",
    inputValue: sopName,
    confirmButtonText: "Simpan",
  });

  if (sop) {
    // ajax update sop

    $.ajax({
      url: "/tugas/sop/update",
      method: "POST",
      data: { id: id, name: sop, _token: CSRF_TOKEN },
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

async function modalAddJudul(id) {
  // HITUNG ALPHABET TERAKHIR DARI MASTER JUDUL
  const sopAPI = `/tugas/sop/count/alphabet/${id}`;
  const inputValue = await fetch(sopAPI)
    .then((response) => response.json())
    .then((data) => data.data.nomor);

  let nextLetter = '';

  if (inputValue === 'NULL') {
      nextLetter = "A";
  } else {
    nextLetter = String.fromCharCode(
      inputValue.charCodeAt(inputValue.length - 1) + 1
    );
  }

  const { value: formValues } = await Swal.fire({
    title: "Tambah Judul Tugas SOP",
    html: `
    <span>${nextLetter}.</span>
    <input type="hidden" id="swal-input1" placeholder="Label" value="${nextLetter}" class="swal2-input" readonly>
      <input id="swal-input2" class="swal2-input" placeholder="Judul">`,
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
    // ajax save judul
    $.ajax({
      url: "/tugas/sop/judul/save",
      method: "POST",
      data: {
        id: id,
        nomor: formValues[0],
        name: formValues[1],
        userId: userId,
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

function onUpdateJudul(element) {
  const status = element.getAttribute("data-status");
  const id = element.getAttribute("data-id");
  const judul = element.getAttribute("data-name");
  const nomor = element.getAttribute("data-nomor");
  const sopId = element.getAttribute("data-sopid");
  if (status === "edit") {
    modalEditJudul(nomor, judul, id);
  } else {
    modaAddTugas(judul, id, sopId);
  }
}

async function modalEditJudul(nomor, judul, id) {
  const { value: formValues } = await Swal.fire({
    title: "Edit Judul",
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
    $.ajax({
      url: "/tugas/sop/judul/update",
      method: "POST",
      data: {
        id: id,
        nomor: formValues[0],
        name: formValues[1],
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

async function modaAddTugas(judul, id, sopId) {
  // hitung nomor terakhir dari master_tugas
  const sopAPI = `/tugas/sop/count/tugas/${id}`;
  const lastNumber = await fetch(sopAPI)
    .then((response) => response.json())
    .then((data) => data.data.nomor);

  const inputNumber = lastNumber + 1;

  const { value: formValues } = await Swal.fire({
    title: `TAMBAH TUGAS DI ${judul}`,
    html: `
          <input  id="swal-input1" value="${inputNumber}" placeholder="Nomor"  class="swal2-input" readonly>
          <input id="swal-input2"  class="swal2-input" placeholder="Tugas">
          <input type="number"  id="swal-input3" placeholder="Nilai"  class="swal2-input">
            `,
    focusConfirm: false,
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Simpan",
    timerProgressBar: true,
    preConfirm: () => {
      if(document.getElementById("swal-input2").value && document.getElementById("swal-input3").value){
        return [
          document.getElementById("swal-input1").value,
          document.getElementById("swal-input2").value,
          document.getElementById("swal-input3").value,
        ];
      }else{
        Swal.showValidationMessage('Lengkapi data');
      }
    },
  });

  if (formValues) {
    // ajax edit judul
    $.ajax({
      url: "/tugas/sop/judul/tugas/save",
      method: "POST",
      data: {
        id: id,
        nomor: formValues[0],
        name: formValues[1],
        nilai: formValues[2],
        userId: userId,
        sopId: sopId,
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
      error: function(error){
        Swal.fire({
          position: "center",
          icon: "warning",
          title: `${error.responseJSON.data.message}`,
          showConfirmButton: false,
          width: 500,
          timer: 900,
        });
      }
    });
  }
}

async function updateTugas(element) {
  const id = element.getAttribute("data-id");
  const tugas = element.getAttribute("data-name");
  const nomor = element.getAttribute("data-nomor");
  const nilai = element.getAttribute("data-nilai");

  const { value: formValues } = await Swal.fire({
    title: `Edit tugas`,
    html: `
              <input  type="number" id="swal-input1"  placeholder="Nomor" value="${nomor}"  class="swal2-input">
              <input id="swal-input2" value="${tugas}"  class="swal2-input" placeholder="Tugas">
              <input type="number"  id="swal-input3" value="${nilai}" placeholder="Nilai"  class="swal2-input">
                `,
    focusConfirm: false,
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Simpan",
    timerProgressBar: true,
    preConfirm: () => {
      return [
        document.getElementById("swal-input1").value,
        document.getElementById("swal-input2").value,
        document.getElementById("swal-input3").value,
      ];
    },
  });

  if (formValues) {
    // ajax edit judul
    $.ajax({
      url: "/tugas/sop/judul/tugas/update",
      method: "POST",
      data: {
        id: id,
        nomor: formValues[0],
        name: formValues[1],
        nilai: formValues[2],
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

function onDelete(element) {
  const id = element.getAttribute("data-id");
  const nomor = element.getAttribute("data-nomor");

  Swal.fire({
    title: `Yakin hapus tugas no. ${nomor}?`,
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
        url: `/tugas/delete`,
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
        error: function (error) {},
      });
    }
  });
}

async function settingUploadFileSop(element) {
  const id = element.getAttribute("data-id");
  const type = element.getAttribute("data-type");
  const value = element.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: `/tugas/settinguploadfile`,
    method: "POST",
    cache: false,
    data: {
      id: id,
      require: value,
      _token: CSRF_TOKEN,
      type: type,
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
      window.location.reload();
    },
  });
}
