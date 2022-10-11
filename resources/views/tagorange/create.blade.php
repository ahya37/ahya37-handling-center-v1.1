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
                                        <h5 class="mb-0 text-primary"> Tambah Group
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('tagorange.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-6">
                                            <label class="form-label">Nama Group (ex : 31 AGS - 8 SEP 2022)</label>
                                            <input type="text" name="group_date" class="form-control @error ('tourcode') is-invalid @enderror number" required>
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
