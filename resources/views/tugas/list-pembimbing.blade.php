@extends('layouts.app')
@section('content')
<div class="page-wrapper">
        <div class="page-content">
				<h6 class="mb-0 ">Jadwal Umrah</h6>
				<hr/>
				<div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0"> <strong>Tourcode : {{ $umrah->tourcode  }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

				@foreach ($pembimbing as $item)
				<div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
				   <div class="d-flex align-items-center">
					   <div class="font-35 text-success"><i class='bx bxs-info-circle'></i></div>
						   <div class="ms-3">
							   <h6 class="mb-0">Pembimbing : {{ $item->nama }} </h6>
							   <div>
								   <a href="{{ route('tugas.jadwalumrah-validasi', $item->aktivitas_umrah_id) }}" class="mt-2 btn btn-sm btn-primary">Klik untuk melakukan validasi tugas</a>
							   </div>
						   </div>
					   </div>
			   </div>
				@endforeach
    </div>
</div>
@endsection
