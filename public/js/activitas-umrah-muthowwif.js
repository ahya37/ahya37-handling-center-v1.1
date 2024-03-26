let urlTourcode = "/api/getdataumrah";
let urlMuthowwif = "/api/getdatamuthowwif";

// INIT DATEPICKER
$(".datepicker").datepicker({
  format: "MM",
  viewMode: "months",
  minViewMode: "months",
  autoclose: true,
  language: "id"
},
$.fn.datepicker.dates['en'] = {
  days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
  daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
  daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
  months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "Desember"],
  monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
  today: "Today",
  clear: "Clear",
  format: "MM",
  titleFormat: "MM", /* Leverages same syntax as 'format' */
  weekStart: 0
});

function onFinish(data) {
  const id = data.id;
  const muthowwif = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin selesai tugas muthowwif : ${muthowwif}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/muthowwif/jadwaltugas/finish`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          let messages = "";
          if (data.data.data === "status") {
            messages = "warning";
          } else {
            messages = "success";
          }
          Swal.fire({
            position: "center",
            icon: `${messages}`,
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
          table.ajax.reload();
        },
      });
    }
  });
}

let tourcode = "";
let muthowwifId = "";
let month = "";
let year = "";

async function allMonth() {
  $("#dates").val("");
  month = "";
  year = "";
  tourcode = "";
  muthowwifId = "";
  urlTourcode = "/api/getdataumrah";
  urlMuthowwif = "/api/getdatamuthowwif";

  table.ajax.reload(null, false);
  initialSelectTourcode(urlTourcode);
  initialSelectMuthowwif(urlMuthowwif);
}

initialSelectTourcode(urlTourcode);
initialSelectMuthowwif(urlMuthowwif);

function initialSelectTourcode(urlTourcode) {
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
      url: urlTourcode,
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.tourcode,
              id: item.tourcode,
            };
          }),
        };
      },
    },
  });
}

$(".pembimbing").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: "Pilih Muthowwif",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: urlMuthowwif,
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

function initialSelectMuthowwif() {
  $(".pembimbing").select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
      ? "100%"
      : "style",
    placeholder: "Pilih Muthowwif",
    allowClear: Boolean($(this).data("allow-clear")),
    ajax: {
      dataType: "json",
      url: urlMuthowwif,
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
}

$(".filter").on("changeDate", async function (selected) {
  const monthSelected = selected.date.getMonth() + 1;
  const yearSelected = selected.date.getFullYear();
  month = monthSelected;
  year = yearSelected;
  (urlTourcode = `/api/getdataumrahbymonth/${month}/${year}`),
    initialSelectTourcode(urlTourcode);
  // GET PEMBIMBING BERDASARKAN BULAN
  urlMuthowwif = `/api/getdatamuthowwif/umrah/${month}/${year}`;
  initialSelectMuthowwif(urlMuthowwif);
  table.ajax.reload(null, false);
});

$(".tourcode").on("change", function () {
  tourcode = $("select[name=tourcode] option").filter(":selected").val();
  table.ajax.reload(null, false);
});

$(".pembimbing").on("change", function () {
  tourcode = "";
  $("#tourcode").empty();
  muthowwifId = $("select[name=pembimbing] option").filter(":selected").val();
  urlTourcode = `/api/getdataumrah/${muthowwifId}`;
  initialSelectTourcode(urlTourcode);
  table.ajax.reload(null, false);
});

const table = $("#data").DataTable({
  pageLength: 100,
  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[0, "asc"]],
  autoWidth: false,
  ajax: {
    url: "/api/dt/muthowwif/list",
    type: "POST",
    data: function (q) {
      q.tourcode = tourcode;
      q.month = month;
      q.year = year;
      q.muthowwif = muthowwifId;
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.tourcode}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        if (row.nonaktif === 1) {
          return '-';
        }else{
          return `<p>${row.muthowwif} (${row.status_tugas})</p>`;
        }
      },
    },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        if (row.nonaktif === 1) {
          return '-'
        }else{
          if (row.status === "finish") {
            let nilai_akhir = calculateGrade(row.nilai_akhir);
            if (nilai_akhir === "A") {
              return `<span class='text-success'>${nilai_akhir}</span>`;
            }else if (nilai_akhir === "B") {
              return `<span class='text-primary'>${nilai_akhir}</span>`;
            }else if (nilai_akhir === "C") {
              return `<span class='text-warning'>${nilai_akhir}</span>`;
            }else if (nilai_akhir === "D") {
              return `<span class='text-danger'>${nilai_akhir}</span>`;
            }else{
				return '-'
			}
          }else{
            return `<span>Dalam proses mengerjakan</span>`;
          }
        }
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        const btnSelesai =
          row.status === `active`
            ? `<button onclick="onFinish(this)" id="${row.id}" value="${row.muthowwif}" class="btn btn-sm btn-warning">Selesai</button>`
            : "";
        const btnDetailTugas = row.nonaktif === 1 ? "" : `<a href='/muthowwif/jadwaltugas/detail/${row.id}' class="btn btn-sm btn-primary">Detail Tugas</a>`;
        const btnHapus = row.nonaktif === 1 ? "" : `<button onclick="onDelete(this)" id="${row.id}" value="${row.muthowwif}" class="btn btn-sm btn-danger">Hapus</button>`;
        return `
                ${btnDetailTugas}
                ${btnSelesai}
                ${btnHapus}
        `;
      },
    },
  ],
});


function calculateGrade(data) {
  let grade = "";
  if (data >= 909) {
    grade = "A";
  }else if (data >= 814 && data <= 908) {
    grade = "B";
  }else if (data >= 622 && data <= 813) {
    grade = "C";
  }else if (data <= 621) {
    grade = "D";
  }else {
	  grade = "Dalam prosess";
  }

  return grade;
}

function calculateGradeCopy(data) {
  let grade = "";
  if (data >= 909 && data >= 957) {
    grade = "A";
  }else if (data >= 814 && data <= 908) {
    grade = "B";
  }else if (data >= 622 && data <= 813) {
    grade = "C";
  }else if (data <= 621) {
    grade = "D";
  }else {
	  grade = "Dalam prosess";
  }

  return grade;
}

function onDelete(data) {
  const id = data.id;
  const muthowwif = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus tugas muthowwif : ${muthowwif}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/muthowwif/jadwaltugas/delete`,
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
        },
      });
      table.ajax.reload();
    }
  });
}
