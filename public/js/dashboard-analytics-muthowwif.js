let urlTourcode = "/api/getdataumrah";
let urlMuthowwif = "/api/getdatamuthowwif";
const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$(".datepicker").datepicker(
	{
		format: "MM",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true,
	},
	($.fn.datepicker.dates["en"] = {
		days: [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
		],
		daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
		daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
		months: [
			"Januari",
			"Februari",
			"Maret",
			"April",
			"Mei",
			"Juni",
			"Juli",
			"Augustus",
			"September",
			"Oktober",
			"November",
			"Desember",
		],
		monthsShort: [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Mei",
			"Jun",
			"Jul",
			"Agu",
			"Sep",
			"Okt",
			"Nov",
			"Des",
		],
		today: "Today",
		clear: "Clear",
		format: "MM",
		titleFormat: "MM" /* Leverages same syntax as 'format' */,
		weekStart: 0,
	})
);

let tourcode = "";
let muthowwifId = "";
let month = "";
let year = "";

async function allMonth() {
	$("#dates").val("");
	$("#pembimbing").val("");
	$("#grade").empty();
	$('#containerGrade').addClass('d-none');
	$('#containerSopN').addClass('d-none');
	month = "";
	year = "";
	tourcode = "";
	muthowwifId = "";
	urlTourcode = "/api/getdataumrah";
	urlMuthowwif = "/api/getdatamuthowwif";

	initialSelectTorucode(urlTourcode);
	initialSelectMuthowwif(urlMuthowwif);
}

initialSelectTorucode(urlTourcode);
initialSelectMuthowwif(urlMuthowwif); 

// FUNGSI SELECT2
function initialSelectTorucode(urlTourcode) {
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

$(".muthowwif").select2({
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
	$(".muthowwif").select2({
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
		initialSelectTorucode(urlTourcode);
	// GET PEMBIMBING BERDASARKAN BULAN
	urlMuthowwif = `/api/getdatamuthowwif/umrah/${month}/${year}`;
	initialSelectMuthowwif(urlMuthowwif);
});

$(".tourcode").on("change", function () {
	tourcode = $("select[name=tourcode] option").filter(":selected").val();
});

$(".muthowwif").on("change", function () {
	tourcode = "";
	$("#tourcode").empty();
	$('#containerGrade').addClass('d-none');
	$('#containerSopN').addClass('d-none');
	$('#error').addClass('d-none');
	muthowwifId = $("select[name=muthowwif] option").filter(":selected").val();
	// GET DATA TOURCODE BERDASARKAN PEMBIMBING
	urlTourcode = `/api/getdataumrah/${muthowwifId}`;
	// initialSelectTorucode(urlTourcode);
	getDataMuthowwif(muthowwifId); 

	// GET DATA GRADE PEMBIMBING
});

function getDataMuthowwif(muthowwifId) {
	$('#error').empty('d-none');
	$.ajax({
		url: `/api/grade/muthowwif`,
		method: "POST",
		data: { id: muthowwifId },
		cache: false,
		beforeSend: function () {
			$('#load').append(
				`<div class="text-center">
					<div class="spinner-border text-warning" role="status">
					  <span class="visually-hidden"></span>
					</div>
				  </div>`
			)
		},
		success: function (data) {
			if (data.data.tourcode.length === 0) {
				$('#error').removeClass('d-none');
				$('#error').append(`
				<div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                               Tidak ada data
                            </div>
                        </div>
                    </div>
				`)
			} else {
				$('#error').addClass('d-none');
				$('#containerGrade').removeClass('d-none');
				$('#containerSopN').removeClass('d-none');
				initialGrafikGrade(data.data);
				initialSopN(data.data.sop_n, data.data.count_sop_n);
			}
		},
		complete: function () {
			$('#load').empty();
		}
	});
}

// <a href='/aktivitas/report/tugas/${row.id}' class="btn btn-sm btn-primary">Cetak </a>

function calculateGrade(data) {
	let grade = "Dalam proses";
	if (data >= 424) {
		grade = "A";
	}
	if (data >= 283 && data <= 423) {
		grade = "B";
	}
	if (data >= 142 && data <= 282) {
		grade = "C";
	}
	if (data <= 141) {
		grade = "D";
	}

	return grade;
}

// function calculateGradeCopy(data) {
	// let grade = "Dalam proses";
	// if (data >= 909 && data >= 957) {
		// grade = "A";
	// }
	// if (data >= 814 && data <= 908) {
		// grade = "B";
	// }
	// if (data >= 622 && data <= 813) {
		// grade = "C";
	// }
	// if (data <= 621) {
		// grade = "D";
	// }

	// return grade;
// }


// GRAFIK
function initialGrafikGrade(data) {
	Highcharts.chart('grade', {
		chart: {
			type: 'line'
		},
		title: {
			text: 'Trafik Grade'
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: data.tourcode
		},
		yAxis: {
			title: {
				text: 'Nilai'
			}
		},
		tooltip: {
			borderColor: '#2c3e50',
			shared: true,
			formatter: function (tooltip) {
				const header = `<span>${this.x}</span><br/>
			  				  <span><b>Nilai : ${this.y}</b></span><br/>
			  				  <span><b>Grade : ${calculateGrade(this.y)}</b></span>
							  `;

				return header;
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: true
			}
		},
		series: [{
			name: 'Grade',
			data: data.nilai
		}
			// , 
			// {
			//     name: 'Tallinn',
			//     data: [-2.9, -3.6, -0.6, 4.8, 10.2, 14.5, 17.6, 16.5, 12.0, 6.5,
			//         2.0, -0.9]
			// }
		]
	});
}

// SOP N / tidak dilaksanakan
function initialSopN(data, count) {
	$('#containerSopN').removeClass('d-none');
	$('#count_sop').text(count);
	let divDataTourcode;
	data.forEach(m => {
		divDataTourcode += showDivDataTourcode(m);
	});
	let divHtmlContainer = $('#datatourcode');
	divHtmlContainer.html(divDataTourcode);
}

function showDivDataTourcode(m) {
	return `
		<tr>
			<td>
				<a href="/dashboard/muthowwif/analytics/sop_n/detail/${m.id}">
				${m.tourcode}
				</a>
			</td>
			<td class="text-right">${m.total_tidak_dilaksanakan}</td>
		</tr>
	`;
}