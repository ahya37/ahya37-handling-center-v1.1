let urlTourcode = "/api/getdataumrah";
let urlPembimbing = "/api/getdatapembimbing";

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
let pembimbingId = "";
let month = "";
let year = "";

async function allMonth() {
	$("#dates").val("");
	month = "";
	year = "";
	tourcode = "";
	pembimbingId = "";
	urlTourcode = "/api/getdataumrah";
	urlPembimbing = "/api/getdatapembimbing";
	// $('#pembimbing').empty();
	// $('#tourcode').empty();

	initialSelectTorucode(urlTourcode);
	initialSelectPembimbing(urlPembimbing);
}

initialSelectTorucode(urlTourcode);
initialSelectPembimbing(urlPembimbing);

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
		url: urlPembimbing,
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

function initialSelectPembimbing() {
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
			url: urlPembimbing,
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
	urlPembimbing = `/api/getdatapembimbing/umrah/${month}/${year}`;
	initialSelectPembimbing(urlPembimbing);
});

$(".tourcode").on("change", function () {
	tourcode = $("select[name=tourcode] option").filter(":selected").val();
});

$(".pembimbing").on("change", function () {
	tourcode = "";
	$("#tourcode").empty();
	pembimbingId = $("select[name=pembimbing] option").filter(":selected").val();
	// GET DATA TOURCODE BERDASARKAN PEMBIMBING
	urlTourcode = `/api/getdataumrah/${pembimbingId}`;
	initialSelectTorucode(urlTourcode);
});

// <a href='/aktivitas/report/tugas/${row.id}' class="btn btn-sm btn-primary">Cetak </a>

function calculateGrade(data) {
	let grade = "Dalam prosess";
	if (data >= 909 && data >= 957) {
		grade = "A";
	}
	if (data >= 814 && data <= 908) {
		grade = "B";
	}
	if (data >= 622 && data <= 813) {
		grade = "C";
	}
	if (data <= 621) {
		grade = "D";
	}

	return grade;
}


// GRAFIK
$(function () {
	"use strict";
	var e = {
		series: [{
			name: "Sessions",
			data: [14, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5]
		}],
		chart: {
			foreColor: "#9ba7b2",
			height: 310,
			type: "area",
			zoom: {
				enabled: !1
			},
			toolbar: {
				show: !0
			},
			dropShadow: {
				enabled: !0,
				top: 3,
				left: 14,
				blur: 4,
				opacity: .1
			}
		},
		stroke: {
			width: 5,
			curve: "smooth"
		},
		xaxis: {
			type: "datetime",
			categories: ["1/11/2000", "2/11/2000", "3/11/2000", "4/11/2000", "5/11/2000", "6/11/2000", "7/11/2000", "8/11/2000", "9/11/2000", "10/11/2000", "11/11/2000", "12/11/2000"]
		},
		title: {
			text: "Grade",
			align: "left",
			style: {
				fontSize: "16px",
				color: "#666"
			}
		},
		fill: {
			type: "gradient",
			gradient: {
				shade: "light",
				gradientToColors: ["#0d6efd"],
				shadeIntensity: 1,
				type: "vertical",
				opacityFrom: .7,
				opacityTo: .2,
				stops: [0, 100, 100, 100]
			}
		},
		markers: {
			size: 5,
			colors: ["#0d6efd"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7
			}
		},
		dataLabels: {
			enabled: !1
		},
		colors: ["#0d6efd"],
		yaxis: {
			title: {
				text: "Nilai"
			}
		}
	};
	new ApexCharts(document.querySelector("#chart1"), e).render();
	e = {
		series: [{
			name: "Total Users",
			data: [240, 160, 671, 414, 555, 257, 901, 613, 727, 414, 555, 257]
		}],
		chart: {
			type: "bar",
			height: 65,
			toolbar: {
				show: !1
			},
			zoom: {
				enabled: !1
			},
			dropShadow: {
				enabled: !0,
				top: 3,
				left: 14,
				blur: 4,
				opacity: .12,
				color: "#17a00e"
			},
			sparkline: {
				enabled: !0
			}
		},
		markers: {
			size: 0,
			colors: ["#17a00e"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7
			}
		},
		plotOptions: {
			bar: {
				horizontal: !1,
				columnWidth: "45%",
				endingShape: "rounded"
			}
		},
		dataLabels: {
			enabled: !1
		},
		stroke: {
			show: !0,
			width: 0,
			curve: "smooth"
		},
		colors: ["#17a00e"],
		xaxis: {
			categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
		},
		fill: {
			opacity: 1
		},
		tooltip: {
			theme: "dark",
			fixed: {
				enabled: !1
			},
			x: {
				show: !1
			},
			y: {
				title: {
					formatter: function (e) {
						return ""
					}
				}
			},
			marker: {
				show: !1
			}
		}
	};

});