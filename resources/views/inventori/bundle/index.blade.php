@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
                {{-- <x:notify-messages /> --}}
				@include('layouts.message')
				<h6 class="mb-0 ">Data Bundel</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2 mt-1">
								<a href="{{ route('bundle-create') }}" class="btn btn-sm btn-primary float-right mb-4 " title="Tambah Baru"><i class="lni lni-circle-plus"></i> Tambah</a>
							</div>
						</div>
						<div class="table">
							<table id="tablePlace" class="table table-hover" style="width:100%">
								<thead>
									<tr>
                                        <th class="col-1">NO</th>
                                        <th class="col-1">Gambar</th>
                                        <th>Item</th>
                                        <th>Stok</th>
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

<form action="" method="post" id="deleteForm">
    @csrf
    @method("DELETE")
    <input type="submit" value="Hapus" style="display:none">
</form>
@endsection

@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/history-inventori.js') }}"></script>
@endpush