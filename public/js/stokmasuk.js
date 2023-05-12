$(function () {
	$(document).ready(function () {
		$('#Transaction-History').DataTable({
			lengthMenu: [[6, 10, 20, -1], [6, 10, 20, 'Todos']]
		});
	});

	new PerfectScrollbar('.product-list');
	
});

function onSelect(data){
	const imgaPreve = $('#product-img-prev');
	const itStok = $('#it_stok');
	const itName = $('#it_name');
	imgaPreve.empty();
	itStok.empty();
	itName.empty();
	itName.empty();

	const image = data.getAttribute("data-image");
	const stok  = data.getAttribute("data-stok");
	const name  = data.getAttribute("data-name");
	const id    = data.id;
	$('#item').val(id);

	imgaPreve.append(`<img src="/storage/${image}" alt=""/>`);
	itStok.text(`${stok} Stok`);
	itName.text(`${name}`);

}


