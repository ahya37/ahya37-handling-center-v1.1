@extends('layouts.app')

@section('content')
<div class="page-wrapper">
        <div class="page-content">
                {{-- <x:notify-messages /> --}}
				@include('layouts.message')
				<h6 class="mb-0 ">Judul Tugas</h6>
				<h6 class="mb-0 ">Silahkan klik nama judul untuk mengisi SOP</h6>
				<hr/>

				@foreach ($jadwal as $item)
				<div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
				   <div class="d-flex align-items-center">
					  
						   <div class="">
							   <h6 class="mb-0 text-success">Tourcode : {{ $item->tourcode }} </h6>
							   {{-- <div>
								   <a href="{{ route('user.aktivitas.detail', $item->id) }}" class="mt-2 btn btn-sm btn-primary">Klik untuk mengisi tahapan tugas</a>
							   </div> --}}
						   </div>
					   </div>

					@php
						$id          = $item->id;
						$title_tugas = $aktitivitasModel->getListSopByAktivitasUmrahId($id);
					@endphp
					<div class="mt-4"></div>
					<h6>Tahapan SOP :</h6>
					@if ($item->jumlah_potensial_jamaah_before == null)
					<div class="d-flex align-items-center mb-4">
						<div class="font-20 text-warning"><i class='bx bxs-info-circle'></i></div>
						
							<div class="ms-3">
							<div>
								<button type="button" class="btn btn-primary" id="{{$item->id}}" data-count="{{$item->count_jamaah}}" value="before" onclick="selectOptionNominal(this)">
									IKUT SERTA DALAM MARTKETING PRA KEBERANGKATAN
								</button>
							</div>
						</div>
					   </div>
					@else
					<div class="d-flex align-items-center mb-4">
						<div class="font-20 text-success"><i class='bx bxs-check-circle'></i></div>
						
							<div class="ms-3">
							<div>
								<button type="button" class="btn btn-secondary" id="{{$item->id}}" value="before" data-count="{{$item->count_jamaah}}" onclick="selectOptionNominal(this)">
									IKUT SERTA DALAM MARTKETING PRA KEBERANGKATAN
								</button>
							</div>
						</div>
					   </div>
					@endif
					@foreach ($title_tugas as $list)
					@if ($list->total_sop == $list->total_terisi)
					<div class="d-flex align-items-center">
						<div class="font-20 text-success"><i class='bx bxs-check-circle'></i></div>
						<div class="ms-3">
							<div>
								<a href="{{route('user.aktivitas.judul.detail', ['aktitivitas_umrah_id' => $aktitivitas_umrah_id,'id' => $list->id ] )}}" class="mt-2 btn btn-sm btn-secondary">{{$list->nama}}</a>
							</div>
						</div>
					 </div>
					 @else 
					 <div class="d-flex align-items-center">
						 <div class="font-20 text-warning"><i class='bx bxs-info-circle'></i></div>
						 <div class="ms-3">
							 <div>
								 <a href="{{route('user.aktivitas.judul.detail', ['aktitivitas_umrah_id' => $aktitivitas_umrah_id,'id' => $list->id ] )}}" class="mt-2 btn btn-sm btn-primary">{{$list->nama}}</a>
							 </div>
						 </div>
					  </div>
					@endif
					@endforeach
					@if ($item->jumlah_potensial_jamaah_after == null)
					<div class="d-flex align-items-center mt-4">
						<div class="font-20 text-warning"><i class='bx bxs-info-circle'></i></div>
						
							<div class="ms-3">
							<div>
								<button type="button" class="btn btn-primary" id="{{$item->id}}" data-count="{{$item->count_jamaah}}" value="after" onclick="selectOptionNominal(this)">
									IKUT SERTA DALAM MARTKETING PASCA KEBERANGKATAN
								</button>
							</div>
						</div>
					   </div>
					   @else 
					   <div class="d-flex align-items-center mt-4">
						<div class="font-20 text-success"><i class='bx bxs-check-circle'></i></div>
						
							<div class="ms-3">
							<div>
								<button type="button" class="btn btn-secondary" id="{{$item->id}}" value="after" data-count="{{$item->count_jamaah}}" onclick="selectOptionNominal(this)">
									IKUT SERTA DALAM MARTKETING PASCA KEBERANGKATAN
								</button>
							</div>
						</div>
					   </div>
					   @endif
					   <div class="d-flex align-items-center mt-4">
                        <div class="font-20 text-warning"><i class='bx bxs-info-circle'></i></div>
                        <div class="col-md-12 col-sm-12">
							<form action="{{route('aktivitas.store.catatan', $aktitivitas_umrah_id)}}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>CATATAN UNTUK EVALUASI (Bisa di edit dan simpan catatan kembali)</label>
									<textarea class="form-control" name="catatan">{{$catatan->catatan ?? ''}}</textarea>
								</div>
								<div class="form-group">
									<button class="btn btn-primary mt-2" type="button" id="btnloading" disabled style="display: none">
										<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
										Sedang menyimpan...
									</button>
									<button class="btn btn-sm btn-primary mt-2" id="saveCatatan" type="submit">Simpan Catatan</button>
								</div>
							</form>
                        </div>
                    </div>
					   
					   
			   </div>
				@endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/list-judul.js') }}"></script>
@endpush
