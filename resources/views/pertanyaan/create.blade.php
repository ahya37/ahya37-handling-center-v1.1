@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
		@include('layouts.back-button')
        @include('layouts.message')
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary" >
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Pertanyaan Baru</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('kuisioner.pertanyaan.save', $kuisioner_id) }}" method="post" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="col-md-12">
                                            <label class="form-label">Pertanyaan</label>
                                            <input type="text" name="isi" class="form-control @error ('isi') is-invalid @enderror"  placeholder="Pertanyaa disini...">
                                            <input type='checkbox' name='required' value='Y' class="mt-2"/> Wajib
                                            @error('isi')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Pilihan Jawaban </label>
                                            <button type="button"  onclick="addKategoriPilihanJawaban()"><i class="fa fa-plus-circle"></i></button>
                                        </div>                                        
                                        <div class="col-md-12">
                                             <select class="multiple-select" name="pilihan[]" data-placeholder="Choose anything" multiple="multiple"></select>
                                        </div>                                        
                                        {{-- <div id="elements">
                                        </div> --}}
                                        <hr>
                                        <div class="col-md-12">
                                            <label class="form-label">Pertanyaan Essay</label>
                                            <button type="button" class="addMoreEssay" id="addMoreEssay" value="Add"><i class="fa fa-plus-circle"></i></button>                                           
                                        </div>
                                        <div id="elements-essay">
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm btn-primary px-5">Simpan</button>
                                        </div>
                                    </form>
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/create-pertanyaan.js') }}"></script>   
@endpush