@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="lni lni-plus font-22 text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-primary"> Tambah SOP</h5>
                            </div>
                            <hr>
                            <form class="row g-3" action="{{ route('tugas.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label class="form-label">SOP</label>
                                    <input type="text" name="name" class="form-control">
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
