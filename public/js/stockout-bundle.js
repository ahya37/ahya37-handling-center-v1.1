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
	const itName = $('#it_name');
	const itCount = $('#it_count');
	imgaPreve.empty();
	itName.empty();
	itCount.empty();

	const name  = data.getAttribute("data-name");
	const count  = data.getAttribute("data-count");
	const id    = data.id;
	$('#item').val(id);

	imgaPreve.append(`<img src="/assets/images/icons/layer.png" alt=""/>`);
	itName.text(`${name}`);
	itCount.text(`${count} Item`);

}


