@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #csth {
            width: 20;
             !important
        }
    </style>
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <a href="{{ route('user.aktivitas.index') }}" class="btn btn-sm btn-primary mb-4"><i
                    class="fa fa-arrow-circle-left" aria-hidden="true"></i>Kembali</a>
            @include('layouts.message')
            <input type="hidden" value="{{ $user_id }}" id="userId">
            {{-- <x:notify-messages /> --}}
            <h6 class="mb-0 ">Tahapan SOP Yang Harus Diisi</h6>
            <input type="hidden" id="sopId" value="{{ $aktitivitas_umrah_petugas_id }}">
            <h6 class="mb-0 "><strong>{{ $judul->nama }}</strong></h6>
            {{-- <h6 class="mt-1 "><strong>Tourcode : {{ $jadwal->tourcode }}</strong></h6> --}}

            <hr />
            <div class="card col-md-10 col-lg-10 col-sm-12">
                <div class="card-body">
                    <div class="table">
                        <table id="listData" class="table table-hover table-border" style="width: 100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="col-1">No</th>
                                    <th class="col-3">Tahapan Tugas</th>
                                    <th>Pelaksanaan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
                    <form action="{{ route('user.petugas.create.aktivitas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="" name="id" class="idTugas" id="idTugas">
                        <input type="hidden" value="Y" name="status">
                        <div class="col-md-12 mb-1" id="mandatory1" style="display: none">
                            <label class="form-label">Foto <span class="text-danger">Diharuskan</span></label>
                            <input type="file" name="image" id="image"
                                class="form-control @error('image') is-invalid @enderror">
                            <span class="text-danger">(Hanya file gambar)</span>
                        </div>
                        <div class="col-md-12 mb-1 " id="mandatory2" style="display: none">
                            <label class="form-label">Dokumen (Jika ada)</label>
                            <input type="file" name="docx" class="form-control @error('docx') is-invalid @enderror">
                            <span class="text-danger">(Hanya file : doc,pdf,ppt)</span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" id="note"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" id="btnloading" disabled style="display: none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </button>

                    <button type="submit" class="btn btn-sm btn-primary" id="btnsave"
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
    <script src="{{ asset('/js/user-activitas-umrah-petugas.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
@endpush
