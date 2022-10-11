@extends('layouts.app')
@section('content')
<div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Jamaah
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('tagorange.addjamaah.store', $id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6">
                                            <label class="form-label">No. Urut</label>
                                            <input type="number" name="no_urut" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Telp.</label>
                                            <input type="type" name="telp" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="address" class="form-control @error ('tourcode') is-invalid @enderror number" required></textarea>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Foto</label>
                                            <input type="file" name="foto" class="form-control @error ('tourcode') is-invalid @enderror number" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
