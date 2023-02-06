@extends('layouts.app')
@push('styles')
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
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Penugasan / Jadwal Baru</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('aktivitas.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-4">
                                           <label class="form-label">Tourcode</label>
                                           <select class="single-select tourcode @error ('umrah') is-invalid @enderror" name="umrah"></select>
                                           @error('umrah')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Kuisioner</label>
                                            <select name='kuisioner_id[]' class='multiple-select kuisioner' required multiple="multiple"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pembimbing</label>
                                            <button type="button" class="addMoreEssay" id="addMoreEssay" value="Add"><i class="fa fa-plus-circle text-primary"></i></button>                                  
                                        </div>
                                        <div class="col-md-12">
                                            <div id="elements-pembimbing"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Asisten Pembimbing</label>
                                            <button type="button" class="addMoreAsisten" id="addMoreAsisten" value="Add"><i class="fa fa-plus-circle text-primary"></i></button>                                  
                                        </div>
                                        <div class="col-md-12">
                                            <div id="elements-asistenpembimbing"></div>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" onclick="check()" id="btnSave" class="btn btn-sm btn-primary px-5">Simpan</button>
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
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('/js/create-activitas-umrah.js') }}"></script>
@endpush
