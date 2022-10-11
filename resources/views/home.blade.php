{{-- @include('layouts.under-dev') --}}
@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
			<h5>Dashboard Tugas Pembimbing</h6>
			<ul style="list-style-type:circle">
				<li>
					Grafik yang ditampilkan adalah pembimbing yang sedang aktif / masih mengerjakan tugas, grafik bisa berubah setiap saat</ul>
				</li>
				<hr/>
				<div id="load"></div>
				<div id="frame">
					
				</div>
				
    </div>
</div>
 <!--start switcher-->
 <div class="switcher-wrapper">
	<div class="switcher-btn"><i class="bx bx-cog bx-spin"></i></div>
	<div class="switcher-body">
	  <div class="d-flex align-items-center">
		<h5 class="mb-0 text-uppercase">Filter</h5>
		<button
		  type="button"
		  class="btn-close ms-auto close-switcher"
		  aria-label="Close"
		></button>
	  </div>
	  <hr />
	  <div class="d-flex align-items-center justify-content-center">
		  <div class="col-md-12">
			<input id="dates" class="col-md-12 btn btn-sm btn-outline-secondary datepicker filter" placeholder="Pilih Bulan"/>
		  </div>
	  </div>
	  <div class="d-flex align-items-center justify-content-between mt-2">
		<div class="col-md-12">
			<select id="tourcode" class="single-select tourcode @error ('tourcode') is-invalid @enderror" name="tourcode"></select>
		</div>
	</div>
	  <div class="d-flex align-items-center justify-content-between mt-2">
		<div class="col-md-12">
			<button onclick="onSubmit()"  class="ml-1 col-md-12 btn btn-sm btn-primary" title="Bulan">Filter</button>
		</div>
	</div>
	  <div class="d-flex align-items-center justify-content-between mt-2">
		<div class="col-md-12">
			<button onclick="allMonth()"  class="ml-1 col-md-12 btn btn-sm btn-outline-secondary" title="Bulan">Tampilkan Semua</button>
		</div>
	</div>
	</div>
  </div>
  <!--end switcher-->

@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/highcharts/js/highcharts.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}">
</script>
@endpush

