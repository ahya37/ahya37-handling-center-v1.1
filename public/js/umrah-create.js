$('input[name="dates"]').daterangepicker({
  locale: {
    format: "MM/DD/YYYY",
    language: "id",
    applyLabel: "Apply",
    cancelLabel: "Cancel",
    fromLabel: "From",
    toLabel: "To",
    customRangeLabel: "Custom",
    daysOfWeek: ["M", "S", "S", "R", "K", "J", "S"],
    monthNames: [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustuas",
      "September",
      "Oktober",
      "November",
      "Desember",
    ],
    firstDay: 1,
  },
});
$(".single-select").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: $(this).data("placeholder"),
  allowClear: Boolean($(this).data("allow-clear")),
});

let year = "";
$("#tahun").on("change", async function () {
  year = $("select[name=tahun] option").filter(":selected").val();
  umrahAPI(year);
});

async function umrahAPI(year) {
  const params = `?year=${year}`;

  await fetch(`https://api.perciktours.com/jadwalumrahbyyeard${params}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json;charset=utf-8",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const result = data.data.jadwal;
      divOptionTourcode(result);
    });
}

umrahAPI(year);

function divOptionTourcode(result) {
  $("#tourcode").empty();
  $("#tourcode").append('<option value="">-Pilih Tourcode-</option>');
  return $.each(result, function (key, item) {
    $("#tourcode").append(
      '<option value="' + item.KODE + '">' + item.KODE + "</option>"
    );
  });
}
