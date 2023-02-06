// CREATE AKTIVITAS
$(".tourcode").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
      ? "100%"
      : "style",
  placeholder: "Pilih Tourcode",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: "/api/getdataumrah",
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data, function (item) {
          return {
            text: `${item.id} - ${item.tourcode}`,
            id: item.id,
          };
        }),
      };
    },
  },
});

// GET DATA PEMBIMBING
$(".pembimbing").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
      ? "100%"
      : "style",
  placeholder: "Pilih Pembimbing",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: "/api/getdatapembimbing",
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data, function (item) {
          return {
            text: item.nama,
            id: item.id,
          };
        }),
      };
    },
  },
});

// GET DATA ASISTEN
// $(".multiple-select").select2({
//   theme: "bootstrap4",
//   width: $(this).data("width")
//     ? $(this).data("width")
//     : $(this).hasClass("w-100")
//     ? "100%"
//     : "style",
//   placeholder: $(this).data("placeholder"),
//   allowClear: Boolean($(this).data("allow-clear")),
//   ajax: {
//     dataType: "json",
//     url: "/api/getasisten",
//     delay: 250,
//     processResults: function (data) {
//       return {
//         results: $.map(data, function (item) {
//           return {
//             text: item.nama,
//             id: item.id,
//           };
//         }),
//       };
//     },
//   },
// });

// GET DATA KUISIONER
$(".kuisioner").select2(
  {
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: "Pilih kuisioner",
    allowClear: Boolean($(this).data("allow-clear")),
    ajax: {
      dataType: "json",
      url: "/api/getkuisioner",
      type: "POST",
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.nama,
              id: item.id,
            };
          }),
        };
      },
    },
  }
);


function initialSelectSop(classFormSop) {
  // GET DATA SOP
  $(classFormSop).select2(
    {
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: "Pilih SOP",
      allowClear: Boolean($(this).data("allow-clear")),
      ajax: {
        dataType: "json",
        url: "/api/getsop",
        type: "POST",
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.name,
                id: item.id,
              };
            }),
          };
        },
      },
    }
  );
}


function initialSelectPembimbing(classForm) {
  // GET DATA PEMBIMBING
  $(classForm).select2(
    {
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: "Pilih Pembimbing",
      allowClear: Boolean($(this).data("allow-clear")),
      ajax: {
        dataType: "json",
        url: "/api/getpembimbing",
        type: "POST",
        delay: 250,
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.nama,
                id: item.id,
              };
            }),
          };
        },
      },
    }
  );
}

// ADD MORE PEMBIMBING DAN SOP
$(document).ready(function () {
  //melakukan proses multiple input
  $("#addMoreEssay").click(function () {
    $.ajax({
      url: "/api/add/form/pembimbing",
      type: "post",
      data: { data: 2 },
      beforeSend: function () {
        $('#load-form').text('Loading form...')
      },
      success: function (response) {
        $("#elements-pembimbing").append(response);
        const classFormPembimbing = ".pembimbing";
        const classFormSop = ".sop"
        initialSelectPembimbing(classFormPembimbing, classFormSop);
        initialSelectSop(classFormSop);
      },
      complete: function () {
        $('#load-form').empty()
      }
    });
  });


  // remove fields group
  $("body").on("click", ".remove-essay", function () {
    $(this).parents(".fieldGroupEssay").remove();
  });
});

// ADD MORE ASISTEN PEMBIMBING DAN SOP ASISTEN
$(document).ready(function () {
  //melakukan proses multiple input
  $("#addMoreAsisten").click(function () {
    $.ajax({
      url: "/api/add/form/asistenpembimbing",
      type: "post",
      data: { data: 2 },
      beforeSend: function () {
        $('#load-form').text('Loading form...')
      },
      success: function (response) {
        $("#elements-asistenpembimbing").append(response);
        const classFormPembimbing = ".asistenpembimbing";
        const classFormSop = ".asistensop";
        initialSelectPembimbing(classFormPembimbing);
        initialSelectSop(classFormSop);

      },
      complete: function () {
        $('#load-form').empty()
      }
    });
  });


  $("body").on("click", ".remove-essay", function () {
    $(this).parents(".fieldGroupEssay").remove();
  });
});

function checkDuplicatData(arr) {
  return new Set(arr).size !== arr.length;
}

function check() {
  document.getElementById("btnSave").type = "button";
  const selectPembimbing = document.getElementsByName('pembimbing_id[]');
  let idPembimbing = [];
  for (let i = 0; i < selectPembimbing.length; i++) {
    const e = selectPembimbing[i];
    idPembimbing.push(e.value);
  }

  const selectAsistenPembimbing = document.getElementsByName('asisten_pembimbing_id[]');
  let idAsistenPembimbing = [];
  for (let i = 0; i < selectAsistenPembimbing.length; i++) {
    const e = selectAsistenPembimbing[i];
    idAsistenPembimbing.push(e.value);
  }

  const mergePembimbing = [...idPembimbing, ...idAsistenPembimbing];
  const checkArray = checkDuplicatData(mergePembimbing);
  if (checkArray) {
    alert('Tidak boleh duplikat pembimbing!')
  } else {
    document.getElementById("btnSave").type = "submit";
  }

}
