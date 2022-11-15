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
            @include('layouts.message')
				<h6 class="mb-0 ">Edit Kusioner</h6>
				<hr/>
				<div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mt-0"> {{ $kuisioner->nama }} <button type="button"  class="btn fa fa-edit" title="Edit Kuisioner" data-bs-toggle="modal" data-bs-target="#exampleFullScreenModal" data-whatever="{{ $kuisioner->nama }}" data-lokasi="{{ $kuisioner->lokasi }}" data-id="{{ $kuisioner->id }}" ></button> </h5>
                                            
                                        </div>
                                    </div>
                                </div>
                 </div>
				<hr/>
				<h6 class="mb-4">Pertanyaan <a href="{{ route('kuisioner.pertanyaan.create', $kuisioner->id) }}" class="btn fa fa-plus-square" title="Tambah Pertanyan"></a></h6>
                @foreach ($pertanyaan as $item)
                <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <p class="mt-0">{{ $item->nomor }}. {{ $item->isi }}</p>
                                                </div>
                                                <div class="col-md-1">
                                                    <button onclick="onUpdatePertanyaan(this)" data-name="{{$item->isi}}" class="btn fa fa-edit" id="{{ $item->id }}" value="{{ $item->nomor }}" title="Edit Pertanyan"></button>
                                                </div>
                                                <div class="col-md-1">
                                                    <button onclick="onDelete(this)" class="btn fa fa-trash text-danger" id="{{ $item->id }}" value="{{ $item->nomor }}" title="Hapus Pertanyan"></button>
                                                </div>
                                            </div>
                                            @php
                                                $pertanyaan_id = $item->id;
                                                $pilihan      = $pilihanModel->where('pertanyaan_id', $pertanyaan_id)->get();
                                            @endphp
                                            @foreach ($pilihan as $item)
                                                <p>({{ $item->nomor }}).{{ $item->isi }} 
                                                    <button class="btn btn-sm fa fa-times-circle text-danger" onclick="onDeletePilihan(this)" id="{{$item->id}}" value="{{$item->nomor}}"></button>
                                                </p>
                                            @endforeach
                                            <div class="col-md-3">
                                                <button onclick="selectOptionPilihan(this)" data-name="{{$item->isi}}"  data-idkuisioner="{{ $pertanyaan_id }}" title="Tambah Pilihan">
                                                    <i class="btn fa fa-plus-square"></i>
                                                    Tambah Pilihan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                 </div>
                @endforeach

                @foreach ($essay as $item)
                <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <p class="mt-0">{{ $item->isi }}</p>
                                                </div>
                                                <div class="col-md-1">
                                                    <button onclick="onDelete(this)" class="btn fa fa-trash" id="{{ $item->id }}" value="{{ $item->nomor }}" title="Hapus Pertanyan"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                 </div>
                @endforeach
    </div>
</div>

 <div class="modal fade" id="exampleFullScreenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('kuisioner.update') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group col-md-12 mb-3">
                    <input type="hidden" class="form-control" name="id" id="recipient-id">
                    <input type="text" class="form-control" name="name" id="recipient-name" autocomplete="off">
                </div>
                <div class="form-group col-md-12 mb-3">
                    <input type="text" class="form-control" name="lokasi" id="recipient-lokasi" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </div>
        </form>
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
<script src="{{ asset('/js/edit-kuisioner.js') }}"></script>

@endpush