@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            {{-- <x:notify-messages /> --}}
            @include('layouts.message')
            <h6 class="mb-0 ">Judul Tugas</h6>
            <h6 class="mb-0 ">Silahkan klik nama judul untuk mengisi SOP</h6>
            <hr />

            @foreach ($jadwal as $item)
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">

                        <div class="">
                            <h6 class="mb-0 text-success">Tourcode : {{ $item->tourcode }} </h6>
                            {{-- <div>
								   <a href="{{ route('user.aktivitas.detail', $item->id) }}" class="mt-2 btn btn-sm btn-primary">Klik untuk mengisi tahapan tugas</a>
							   </div> --}}
                        </div>
                    </div>

                    @php
                        $id = $item->id;
                        $title_tugas = $aktitivitasModel->getListSopByAktivitasUmrahId($id);
                    @endphp
                    <div class="mt-4"></div>
                    
                    @foreach ($title_tugas as $list)
                        @if ($list->total_sop == $list->total_terisi)
                            <div class="d-flex align-items-center">
                                <div class="font-20 text-success"><i class='bx bxs-check-circle'></i></div>
                                <div class="ms-3">
                                    <div>
                                        <a href="{{ route('user.petugas.aktivitas.judul.detail', ['aktitivitas_umrah_petugas_id' => $aktitivitas_umrah_petugas_id, 'id' => $list->id]) }}"
                                            class="mt-2 btn btn-sm btn-secondary">{{ $list->nama }}</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <div class="font-20 text-warning"><i class='bx bxs-info-circle'></i></div>
                                <div class="ms-3">
                                    <div>
                                        <a href="{{ route('user.petugas.aktivitas.judul.detail', ['aktitivitas_umrah_petugas_id' => $aktitivitas_umrah_petugas_id, 'id' => $list->id]) }}"
                                            class="mt-2 btn btn-sm btn-primary">{{ $list->nama }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('/js/list-judul.js') }}"></script>
@endpush
