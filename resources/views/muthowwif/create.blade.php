@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
            <h6 class="mb-0 ">Muthawwif</h6>
				<hr/>
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Muthawwif Baru</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('muthowwif.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control @error ('name') is-invalid @enderror" required placeholder="name here...">
                                            @error('name')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Expired Passport</label>
                                            <input type="text" name="expired_passport" class="form-control @error ('expired_passport') is-invalid @enderror" required placeholder="expired passport here...">
                                            @error('expired_passport')
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
                                                    <div class="text-white">Email ini digunakan untuk login pembimbing, contoh: namamuthowwif@percik.com</div>
                                            </div>
                                            <input type="text" name="email" class="form-control @error ('email') is-invalid @enderror" required placeholder="email here...">
                                            @error('email')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12"></div>
                                        <div class="col-md-6">
                                            <label class="form-label">Password (<span class="text-danger">Deafult: 12345678</span>)</label>
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

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
<script>
    $(function() {
      $('input[name="expired_passport"]').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10)
      });
    });
    </script>
@endpush

