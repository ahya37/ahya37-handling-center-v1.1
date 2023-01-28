@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/font-awesome/all.min.css')}}" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
        @include('layouts.message')
				<h6 class="mb-0 ">Tugas Pembimbing Umrah</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2 mt-1">
								<div class="mb-4">
									<button onclick="allMonth()"  class="btn btn-sm btn-outline-secondary ml-1" title="Bulan">Tampilkan Semua</button>
							</div>
							</div>
							<div class="col-md-2 mt-1">
								<div class="mb-4">
									<input id="dates" class="col-md-12 btn btn-sm btn-outline-secondary datepicker filter" placeholder="Pilih Bulan"/>
							</div>
							</div>
							<div class="col-md-3">
									<select id="pembimbing" class="single-select pembimbing @error ('pembimbing') is-invalid @enderror" name="pembimbing"></select>
							</div>
							<div class="col-md-3">
								<select id="tourcode" class="single-select tourcode @error ('tourcode') is-invalid @enderror" name="tourcode"></select>
							</div>
							<div class="col-md-1 mt-1">
								<a href="{{ route('aktivitas.create') }}" class="btn btn-sm float-right mb-4 text-primary" title="Tambah Baru"><i class="lni lni-circle-plus"></i> </a>
							</div>
						</div>
						<hr>
						<div class="table-responsive">
							<table id="data" class="table table-hover" style="width:100%">
								<thead>
									<tr>
										<th>Tourcode</th>
                                        <th>Pembimbing / Asisten</th>
                                        <th>Grade</th>
                                        <th>Opsi</th>
                                        <th>ID</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
    </div>
</div>
@endsection
@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="{{asset('vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{{ asset('/js/activitas-umrah.js') }}"></script>
@endpush