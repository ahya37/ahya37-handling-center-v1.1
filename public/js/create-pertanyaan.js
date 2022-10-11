$(".multiple-select").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: $(this).data("placeholder"),
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: "/api/getpilihan",
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

$(document).ready(function () {
  //melakukan proses multiple input
  $("#addMoreEssay").click(function () {
    $.ajax({
      url: "/api/add/form/essay",
      type: "post",
      data: { data: 2 },
      success: function (response) {
        // Append element
        $("#elements-essay").append(response);
      },
    });
  });

  // remove fields group
  $("body").on("click", ".remove-essay", function () {
    $(this).parents(".fieldGroupEssay").remove();
  });
});
