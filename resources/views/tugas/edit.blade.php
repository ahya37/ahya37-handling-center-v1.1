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
         <div class="row">
                        <div class="col-xl-8 col-sm-12">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Edit Tugas </h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('tugas.update', $tugas->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12">
                                            <label class="form-label">Nomor</label>
                                            <input type="text" name="nomor" value="{{ $tugas->nomor }}" class="form-control" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">Tugas</label>
                                             <textarea name="nama" placeholder="Tugas disini..." class="form-control @error ('nama') is-invalid @enderror" cols="4" rows="8">
                                                {{ $tugas->nama }}
                                            </textarea>
                                            @error('nama')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary px-5">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-12">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        
                                        <h5 class="mb-0 text-primary"> Tukar Nomor </h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('tugas.tukarnomor', $tugas->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12">
                                            <label class="form-label">Nomor Awal</label>
                                            <input type="number" name="nomor_awal" readonly value="{{ $tugas->nomor }}" class="form-control" >
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Nomor Tukar</label>
                                            <select class="single-select nomor_tukar @error ('nomor_tukar') is-invalid @enderror" name="nomor_tukar">
                                                @foreach ($nomor_tugas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nomor }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary px-5">Simpan</button>
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
<script>
    $('.nomor_tukar').select2();
</script>
@endpush
