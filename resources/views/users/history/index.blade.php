@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            {{-- <x:notify-messages /> --}}
            <h6 class="mb-0 ">Histori Jadwal Umrah</h6>

            <hr />
            @foreach ($result as $item)
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                        <div class="ms-3 col-md-6">
							<div class="row">
								<div class="col-md-10">
									<h6 class="mb-0 text-success">Tourcode : {{ $item['tourcode'] }} </h6>

									<a href="{{ route('user.aktivitas.historydetail', $item['id']) }}"
										class="mt-2 btn btn-sm btn-primary">Klik untuk melihat histori tugas</a>
								</div>
								<div class="col-md-2 mt-1">
									@if ($item['nilai_akhir'] == 'A')
									<h6 class="mb-0 text-success text-center">Grade  </h6>
									<div class="text-success ms-auto ">
										<h5 class="mt-2 text-success text-center">{{$item['nilai_akhir']}}</h5> 
									</div>
									@elseif($item['nilai_akhir'] == 'B')
									<h6 class="mb-0 text-primary text-center">Grade </h6>
									<div class="text-primary ms-auto ">
										<h5 class="mt-2 text-primary text-center">{{$item['nilai_akhir']}}</h5> 
									</div>
									@elseif($item['nilai_akhir'] == 'C')
									<h6 class="mb-0 text-warning text-center">Grade </h6>
									<div class="text-warning ms-auto ">
										<h5 class="mt-2 text-warning text-center">{{$item['nilai_akhir']}}</h5> 
									</div>
									@else
									<h6 class="mb-0 text-danger text-center">Grade </h6>
									<div class="text-danger ms-auto ">
										<h5 class="mt-2 text-danger text-center">{{$item['nilai_akhir']}}</h5> 
									</div>
									@endif
								</div>
							</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
