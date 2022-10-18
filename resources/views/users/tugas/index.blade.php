@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            {{-- <x:notify-messages /> --}}
            <h6 class="mb-0 ">Jadwal Umrah</h6>

            <hr />
            @foreach ($result as $item)
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h6 class="mb-0 text-success">Tourcode : {{ $item['tourcode'] }} </h6>
    
                                        <a href="{{ route('user.form.isitugas', $item['id']) }}"
                                            class="mt-2 btn btn-sm btn-primary">Klik untuk mengisi SOP</a>
                                        <a target="_blank" href="{{ route('kuisioner.umrah.view', $item['url']) }}" 
                                            class="mt-2 btn btn-sm btn-primary">Link Kuisioner (Silahkan klik dan copy link nya untuk dibagikan)</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                    <table class="table mb-0 table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <i class="bx bxs-checkbox me-2 font-22 text-info"></i>
                                                        </div>
                                                        <div>Jumlah Jamaah</div>
                                                    </div>
                                                </td>
                                                <td>{{$item['count_jamaah']}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <i class="bx bx-user-check font-22 text-success"></i>
                                                        </div>
                                                        <div>Responden Kuisioner</div>
                                                    </div>
                                                </td>
                                                <td>{{$item['responden_kuisioner']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
