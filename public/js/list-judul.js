// ISI TUGAS TANPA FILE UPLOAD
async function selected(elem) {
  const status = elem.value;
  const id = elem.id;
  const countJamaah = elem.getAttribute("data-count");

  let inputLabel = "";
  if (status === "before") {
    inputLabel = `Berapa jumlah yang pembimbing referalkan dari group nya (${countJamaah} Jamaah) ?`;
  } else {
    inputLabel = `Berapa jumlah jamaah yang pembimbing dapatkan untuk program umrah selanjutnya dari group nya  (${countJamaah} Jamaah)?`;
  }

  const { value: text } = await Swal.fire({
    input: "number",
    title: "Notes:",
    text: inputLabel,
    inputPlaceholder: "Isi disini berupa angka saja",
    inputAttributes: {
      "aria-label": "Type your message here",
    },
    showCancelButton: true,
    cancelButtonText: "Batal",
  });
  if (text) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: "/api/createTugasmarketing",
      method: "POST",
      cache: false,
      data: {
        id: id,
        status: status,
        count: text,
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
        window.location.reload();
      },
    });
  }
}

async function selectOptionNominal(elem) {
  const status = elem.value;
  const id = elem.id;
  const countJamaah = elem.getAttribute("data-count");

  let inputLabel = "";
  if (status === "before") {
    inputLabel = `Berapa jumlah yang pembimbing referalkan dari group nya (${countJamaah} Jamaah) ?`;
  } else {
    inputLabel = `Berapa jumlah jamaah yang pembimbing dapatkan untuk program umrah selanjutnya dari group nya  (${countJamaah} Jamaah)?`;
  }

  let Pilihan = [];

  for (let i = 0; i <= countJamaah; i++) {
    Pilihan.push(i);
  }


  const { value: nominal } = await Swal.fire({
    title: "Notes",
    text: inputLabel,
    input: "select",
    inputOptions: { Pilihan },
    inputPlaceholder: "Pilih Jumlah",
    showCancelButton: true,
    inputValidator: (value) => {
      return new Promise((resolve) => {
        resolve();
      });
    },
  });

  if (nominal) {
    Swal.fire(`Pilihan Anda: ${nominal}`);
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: "/api/createTugasmarketing",
      method: "POST",
      cache: false,
      data: {
        id: id,
        status: status,
        count: nominal,
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

        // Swal.fire({
        //   position: "center",
        //   icon: "success",
        //   title: `${data.data.message}`,
        //   showConfirmButton: false,
        //   width: 500,
        //   timer: 900,
        // });
        window.location.reload();
      },
    });
  } else {
    // Swal.fire(`Pilih Jumlah`);
  }
}

// GET JUMLAH JAMAAH BERDASARKAN UMRAH
// async function getCountJamaah(id){
//   const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

//   return fetch('/api/umrah/count', {
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json',
//     },
//     body: JSON.stringify({
//       id: id,
//       _token: CSRF_TOKEN
//     })
//   }).then(response => response.json()).then(data => {
//     return data;
//   }).catch((error) => {
//   });
// }

$('#saveCatatan').on('click', function(){
  $('#saveCatatan').hide();
  $('#btnloading').show();
});
