@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
		@include('layouts.back-button')

				<h6 class="mb-0 ">Detail Tugas Umrah - <span class="text-danger">Status Tidak Dilaksanakan</span></h6>
				<h6 class="mb-0 ">Standard Operating Prosedur (SOP) Bimbingan Ibadah Umrah Untuk Pembimbing Ibadah</h6>
				<hr/>
				<div class="col-md-9 col-sm-12 mb-3">
					<div class="flex-grow-1">
						<div class="loading" style="display: none"></div>
						<button class="btn btn-sm btn-primary" id="btnValidate" onclick="validate()">Nilai</button>
					</div>
				</div>
				@foreach ($results as $item)
					<div class="col-md-7 card">
							<div class="card-header">{{$item['nomor']}}. {{$item['judul']}}</div>
							<div class="card-body">
								<div class="table">
									<table class="table table-hover">
										<thead>
											<tr>
												<th></th>
												<th class="col-1">No</th>
												<th class="col-7">Tugas</th>
												<th >Nilai</th>
												<th >Keterangan</th>
												<th >Nilai Poin</th>
												<th >Tanggal</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($item['sop'] as $sop)
											<tr>
												<td>
													<input type="checkbox" name="validate[]" class="validate"
                                                            value="{{ $sop->id }}" id="{{ $sop->id }}">
												</td>
												<td>{{$sop->nomor_tugas}}</td>
												<td>{{$sop->nama_tugas}}</td>
												<td>{{$sop->nilai_akhir}}</td>
												<td>{{$sop->alasan}}</td>
												<td>{{$sop->nilai_point ?? ''}}</td>
												<td>{{date('d-m-Y', strtotime($sop->updated_at))}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
					</div>
				@endforeach
    </div>
</div>
@endsection
@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/detail-activitas-umrah-status-null.js') }}"></script>
@endpush