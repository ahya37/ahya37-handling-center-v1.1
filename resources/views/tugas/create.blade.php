@extends('layouts.app')
@push('styles')
	<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />

@endpush
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
                                <div class="col-md-6">
                                    <label class="form-label">Kategori SOP</label>
                                   <select class="single-select @error ('kuisioner') is-invalid @enderror" name="kategorimastersop" required>
                                        @foreach ($kategori_master_sop as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                   </select>
                                </div>
                                <div class="col-md-6">
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
@push('scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/create-sop.js') }}"></script>   
@endpush