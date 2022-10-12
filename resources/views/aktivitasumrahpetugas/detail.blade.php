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
            @include('layouts.back-button')

            <h6 class="mb-0 ">Detail Tugas Umrah</h6>
            <h6 class="mb-0 ">{{ $sop->name }}</h6>
            <hr />
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/profile-user.png" class="user-img" width="60" alt="user avatar">
                        <div class="flex-grow-1 ms-2">
                            <h5 class="mt-0">{{ $title }} : {{ $aktitivitas->petugas }}</h5>
                            <p class="mb-0">Tourcode : {{ $aktitivitas->tourcode }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card radius-10 col-md-9 col-sm-12">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
							<div class="loading" style="display: none"></div>
                            <button class="btn btn-sm btn-primary" id="btnValidate" onclick="validate()">Validasi</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                @forelse  ($judul_sop as $item)
                    <div class="col-md-9 col-sm-12">
                        <div class="card radius-10">
                            <div class="card-header">
                                {{ $item->nama }}
                            </div>

                            @php
                                $jdudul_id = $item->id;
                                $sop = $aktitivitasModel->getListTugasByMasterJudulIdByAktitivitasUmrah($aktitivitas->id, $jdudul_id);
                            @endphp
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" style="width: 100%">
                                        <thead>
                                            <th></th>
                                            <th>No</th>
                                            <th class="col-5">Tugas</th>
                                            <th>Pelaksanaan</th>
                                            <th>Note/Alasan</th>
                                            <th>Foto</th>
                                            <th>Dokumen</th>
                                            <th>Nilai</th>
                                            <th>Updated</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($sop as $list)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="validate[]" class="validate"
                                                            value="{{ $list->id }}" id="{{ $list->id }}" {{$list->validate == 'Y' ? 'checked' : ''}}>
                                                    </td>
                                                    <td>{{ $list->nomor_tugas }}</td>
                                                    <td>{{ $list->nama_tugas }}</td>
                                                    <td>
                                                        @if ($list->status == 'Y')
                                                            Ya
                                                        @elseif($list->status == 'N')
                                                            Tidak
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td>{{ $list->alasan }}</td>
                                                    <td>
                                                        @if ($list->file == null)
                                                            <small>-</small>
                                                        @else
                                                            <a target="_blank" href="{{ asset('storage/' . $list->file) }}">
                                                                <img src="{{ asset('storage/' . $list->file) }}">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($list->file_doc == null)
                                                            <small>-</small>
                                                        @else
                                                            <a target="_blank"
                                                                href="{{ asset('storage/' . $list->file_doc) }}">
                                                                {{ $list->file_doc_name }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($list->status == 'N')
                                                        <button onclick="NilaiPertimbangan(this)" data-id="{{$list->id}}" class="text-primary">
                                                            {{ $list->nilai_akhir }}
                                                        </button>
                                                        @else
                                                        {{ $list->nilai_akhir }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d-m-Y H:i', strtotime($list->updated_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 cek">
                        <div class="card radius-10">
                            <div class="card-body text-center ">
                                <p class="text-center">BELUM ADA TUGAS UNTUK : {{ $sop->name }} </p>
                                <button class="btn btn-sm btn-primary" type="button" onclick="cekAndPerbaruiTugas()">Cek
                                    dan Perbarui Tugas</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 loading" style="display: none">
                        <div class="card radius-10">
                            <div class="card-body text-center">
                            </div>
                        </div>
                    </div>
                @endforelse
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

    <script src="{{ asset('/js/detail-activitas-umrah.js') }}"></script>
@endpush
