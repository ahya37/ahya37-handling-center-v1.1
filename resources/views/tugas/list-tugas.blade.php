@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
                @include('layouts.message')
				<h6 class="mb-0 ">Jadwal  Umrah</h6>
				<hr/>
				
				<div class="card">
					<div class="card-body">
						<div class="d-flex align-items-center mb-4">
                            <div class="flex-grow-1 ms-2">
                                <div class="dropdown">
								<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
									Filter
								</button>
								<ul class="row dropdown-menu" aria-labelledby="dropdownMenuButton1">
									<li>
                                		<button id="dates" class="datepicker filter" title="Bulan"><i class="fa fa-calendar text-dark mx-auto"></i></button>
									</li>
									<li>
                                		<button onclick="allMonth()"  class="" title="Bulan">Semua</button>
									</li>
								</ul>
								</div>
							</div>
							
                         </div>
						 <hr>
						<div class="table-responsive">
							<table id="data" class="table table-hover table-border" style="width:100%">
								<thead>
									<tr>
                                        <th>Tourcode</th>
                                        <th>Tanggal</th>
                                        <th>Opsi</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('/js/jadwal-umrah-active.js') }}"></script>
@endpush
