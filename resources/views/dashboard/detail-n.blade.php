@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
		@include('layouts.back-button')
		<input type="hidden" value="{{$id}}" id="aktivitasId">

				<h6 class="mb-0 ">Detail Tugas Umrah - <span class="text-danger">Status Tidak Dilaksanakan (N)</span> </h6>
				<h6 class="mb-0 ">Standard Operating Prosedur (SOP) Bimbingan Ibadah Umrah Untuk Pembimbing Ibadah</h6>
				<hr/>
				{{-- <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                         <img src="/assets/images/profile-user.png" class="user-img" width="60" alt="user avatar">
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mt-0">Pembimbing : {{ $aktitivitas->pembimbing  }}</h5>
                                            <p class="mb-0">Tourcode : {{ $aktitivitas->tourcode  }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table">
							<table id="listData" class="table table-hover table-border" style="width: 100%">
								<thead>
									<tr>
										<th></th>
										<th>No</th>
                                        <th class="col-3">Tahapan Tugas</th>
                                        <th>Pelaksanaan</th>
                                        <th >Alasan Tidak Dilaksanakan</th>
                                        <th >Tanggal & Waktu</th>
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
<script src="{{ asset('/js/detail-activitas-umrah-status-n.js') }}"></script>
@endpush