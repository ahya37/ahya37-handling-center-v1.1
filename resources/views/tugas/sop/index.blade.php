@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')

            <h6 class="mb-0 ">SOP</h6>
            <hr />

            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab"
                                    aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Pembimbing</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab"
                                    aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Petugas</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                <a href="{{ route('tugas.create') }}" class="btn btn-primary btn-sm float-right mb-4"><i
                                        class="lni lni-circle-plus"></i> Tambah Baru</a>
                                <div class="table">
                                    <table id="tablePlace" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="col-6">SOP</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                <a href="{{ route('tugas.create.petugas') }}"
                                    class="btn btn-primary btn-sm float-right mb-4"><i class="lni lni-circle-plus"></i>
                                    Tambah Baru</a>
                                <div class="table">
                                    <table id="sopPetugas" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="col-6">SOP</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('/vendor/datatables/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('/js/sop.js') }}"></script>
@endpush
