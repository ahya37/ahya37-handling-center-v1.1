@extends('layouts.app')
@push('styles')
{{-- <link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
{{-- <style>
	#csth {
		width: 20; !important
	}
</style> --}}

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
                {{-- <x:notify-messages /> --}}
				<a href="{{ route('user.aktivitas.history') }}" class="btn btn-sm btn-primary mb-4"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>Kembali</a>

				<h6 class="mb-0 ">Tahapan Tugas Yang Harus Diisi</h6>
				<h6 class="mt-1 "><strong>Tourcode : {{ $jadwal->tourcode }}</strong></h6>

				<hr/>
				<div class="row">
					@foreach ($judul_sop as $item)
                    <div class="col-md-8">
                        <div class="card radius-10">
							<div class="card-header">{{$item->nama}}</div>

							@php
								$jdudul_id = $item->id;
								$sop       = $aktitivitasModel->getListTugasByMasterJudulId($jdudul_id);
							@endphp
							<div class="card-body">
								<div class="table-responsive">
								<table class="table table-hover" style="width: 100%">
									<thead>
										<th>No</th>
										<th class="col-5">Tugas</th>
										<th>Pelaksanaan</th>
										<th>Note/Alasan</th>
										<th>Foto</th>
										<th>Updated</th>
									</thead>
									<tbody>
										@foreach ($sop as $list)
										<tr>
											<td>{{$list->nomor_tugas}}</td>
											<td>{{$list->nama_tugas}}</td>
											<td>{{$list->status == 'Y' ? 'Ya' : 'Tidak'}}</td>
											<td>{{$list->alasan}}</td>
											<td>
												@if ($list->file == NULL)
													<small>-</small>
												@else
												<a target="_blank" href="{{asset('storage/'.$list->file)}}">
													<img src="{{asset('storage/'.$list->file)}}">
												</a>
												@endif
											</td>
											<td>{{date('d-m-Y H:i', strtotime($list->updated_at))}}</td>
										</tr>
										@endforeach
									</tbody>

								</table>
							</div>
							</div>
                         </div>				
                    </div>
					@endforeach
                </div>
				{{-- <hr> --}}
				{{-- <div class="card col-md-10  col-sm-12">
					<div class="card-body">
						<div class="table-responsive">
							<table id="listData" class="table table-hover table-border">
								<thead>
									<tr>
										<th></th>
										<th class="col-1">No</th>
                                        <th class="col-5">Tahapan Tugas</th>
										<th>Pelaksanaan</th>
										<th>Alasan</th>
										<th>Tanggal & Waktu</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div> --}}
    </div>
</div>
@endsection
{{-- @push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('/js/history-user-activitas-umrah.js') }}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
@endpush --}}