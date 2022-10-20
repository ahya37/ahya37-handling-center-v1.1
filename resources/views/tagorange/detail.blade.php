@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #image-preview{
        display:none;
        width : 250px; 
        height : 300px;
    }
    </style>
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
                                <h5 class="mb-0 text-primary"> Detail Group
                            </div>
                            <hr>
                            <form class="row g-3" action="{{ route('tagorange.update',$tag_orange->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div clases="col-md-6">
                                    <label class="form-label">Nama Group (ex : 31 AGS - 8 SEP 2022)</label>
                                    <input type="text" name="group_date" value="{{ $tag_orange->group_date }}"
                                        class="form-control @error('tourcode') is-invalid @enderror number" required>
                                    @error('count')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-primary px-5">Ubah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary"> Cetak Jamaah
                            </div>
                            <hr>
                            <form id="cetak" class="row g-3" action="{{ route('tagorange.export', $id) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-2">
                                    <input type="number" name="start"
                                        class="form-control @error('tourcode') is-invalid @enderror number" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="end"
                                        class="form-control @error('tourcode') is-invalid @enderror number" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="label"
                                        class="form-control @error('label') is-invalid @enderror" placeholder="Label, Ex: UMRAH GROUP" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-sm btn-primary mt-1">Cetak</button>
                                    {{-- <a
                                        onclick="event.preventDefault();
                                            document.getElementById('cetak').submit();"
                                        class="btn btn-sm btn-primary mt-1">Cetak</a> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Daftar Jamaah</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-title d-flex align-items-center">
                                        <form id="import" action="{{route('tagorange.jamaah.import', $id)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" class="form-control" name="file">
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card-title d-flex align-items-center">
                                        <button  onclick="event.preventDefault();
                                        document.getElementById('import').submit();" class="btn btn-sm btn-primary px-5">Import</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table">
                                <table id="tablePlace" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No.Urut</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>No.Telp</th>
                                            <th>Email</th>
                                            <th>Alamat</th>
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

     <!-- Modal -->
     <div class="modal fade hide" id="myModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Silahkan Isi</h4>
                    <button type="button" class="close text-danger" onclick="closeModal()"
                        data-dismiss="modal">&times;</button>
                </div>
                <!-- <span id="notif" style="display: none" class="alert alert-danger"></span> -->

                <div class="modal-body">
                    <form id="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mb-1">
                            <label class="form-label">Foto <span class="text-danger">Diharuskan</span></label>
                            <input type="hidden" id="idJamaah" name="id">
                            <input type="file" name="foto" id="image" onchange="previewImage();"
                                class="form-control @error('foto') is-invalid @enderror" required>
                            <span class="text-danger">(Hanya file gambar)</span>
                        </div>
                        <div class="col-md-12 mb-1">
                            <img id="image-preview" alt="image preview"/>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" id="btnloading" disabled style="display: none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </button>

                    <button type="submit" class="btn btn-sm btn-primary upload-image" id="btnsave"
                        data-dismiss="modal">Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btnclose" onclick="closeModal()"
                        data-dismiss="modal">Tutup</button>
                    </form>
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
    <script src="{{ asset('/js/detail-tagorange.js') }}"></script>
@endpush
