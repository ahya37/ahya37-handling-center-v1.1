@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            {{-- <x:notify-messages /> --}}
            <h6 class="mb-0 ">Jadwal Umrah</h6>

            <hr />
            @if (count($result) != 0)
                @foreach ($result as $item)
                    <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h6 class="mb-0 text-success">Tourcode : {{ $item['tourcode'] }} </h6>
        
                                            <a href="{{ route('user.muthowwif.form.isitugas', $item['aktivitas_umrah_id']) }}"
                                                class="mt-2 btn btn-sm btn-primary">Klik untuk mengisi SOP</a>

                                            <div class="row">
                                                @foreach ($item['kuisioner'] as $kuisioner)
                                                <div class="col-md-8">
                                                        <a target="_blank" href="{{ route('kuisioner.umrah.view', $kuisioner->url) }}" 
                                                            class="mt-2 btn btn-sm btn-primary"> {{$kuisioner->kuisioner}} <br>Link Kuisioner (Silahkan klik dan copy link nya untuk dibagikan)
                                                        </a>
                                                </div>
                                                <div class="col-md-4">
                                                    Jamaah : {{$kuisioner->count_jamaah}}
                                                    <br>
                                                    Responden : {{$kuisioner->jumlah_responden}}
                                                </div>
                                                @endforeach
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h6 class="mb-0 alert bg-warning">Jadwal belum dibuat !</h6>
            @endif
        </div>
    </div>
@endsection
