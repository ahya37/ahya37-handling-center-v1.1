@extends('layouts.app')
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
                                        <h5 class="mb-0 text-primary"> Edit Petugas</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('petugas.update', $petugas->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" value="{{ $petugas->nama }}" class="form-control @error ('name') is-invalid @enderror" required placeholder="name here...">
                                            @error('name')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <h5 class="mb-0 text-primary"> Akun</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <div class="alert alert-info border-0 bg-info alert-dismissible fade show">
                                                    <div class="text-white">Email ini digunakan untuk login pembimbing, contoh: namapembimbing@percik.com</div>
                                            </div>
                                            <input type="text" name="email" value="{{ $user->email }}" class="form-control @error ('email') is-invalid @enderror" required placeholder="email here...">
                                            @error('email')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12"></div>
                                        <div class="col-md-6">
                                            <input type="password" name="password" class="form-control @error ('password') is-invalid @enderror" placeholder="password here...">
                                            @error('password')
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
