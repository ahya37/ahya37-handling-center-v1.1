@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Tambah Bundel</h5>
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{ route('bundle-store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control @error ('name') is-invalid @enderror name">
                                            @error('name')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Pilih Item</label>
                                            <div class="table">
                                                <table id="tablePlace" class="table table-hover" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-1">Pilih</th>
                                                            <th class="col-1">Gambar</th>
                                                            <th>Item</th>
                                                            <th>Stok</th>
                                                            <th class="col-2">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Keterangan</label>
                                            <textarea  name="note" class="form-control"></textarea>
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
<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('/js/create-bundle.js') }}"></script>
<script src="{{ asset('js/number-only.js') }}"></script>   

@endpush