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
            text: item.tourcode,
            id: item.id,
          };
        }),
      };
    },
  },
});

// CEK JIKA TOURCODE MEMILIKI SOP ASISTEN
$(".tourcode").on("change", function () {
  const id = $("select[name=umrah] option").filter(":selected").val();
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

  // ajax cek asisten
  $.ajax({
    url: "/api/getcekasisten",
    method: "POST",
    data: { id: id, _token: CSRF_TOKEN },
    beforeSend: function () {
      $(".asisten").hide();
      $(".loading").show();
      $(".loading").append(
        `<div class="text-center">
              <button class="btn btn-primary" type="button" disabled>
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Cek Asisten ...
            </button>
          </div>`
      );
    },
    success: function (data) {
      const result = data.data.data;
      if (result !== null) {
        $(".asisten").show();
        $(".asisten").attr("required", true);
      } else {
        $(".asisten").hide();
        $(".asisten").attr("required", false);
      }
      // Swal.fire({
      //   position: "center",
      //   icon: "success",
      //   title: `${data.data.message}`,
      //   showConfirmButton: false,
      //   width: 500,
      //   timer: 900,
      // });
      // window.location.reload();
    },
    complete: function () {
      $(".loading").hide();
      $(".loading").empty();
    },
  });
});

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
    url: "/api/getasisten",
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


$(".petugas").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: "Pilih Petugas",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: "/api/getdatapetugas",
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