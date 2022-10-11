@extends('layouts.app')
@section('content')
<div class="page-wrapper">
        <div class="page-content">
                {{-- <x:notify-messages /> --}}
				<h6 class="mb-0 ">Histori Jadwal Umrah</h6>

				<hr/>
				@foreach ($jadwal as $item)
				<div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
				   <div class="d-flex align-items-center">
					   <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
						   <div class="ms-3">
							   <h6 class="mb-0 text-success">Tourcode : {{ $item->tourcode }} </h6>
							   <div>
								   <a href="{{ route('user.aktivitas.historydetail', $item->id) }}" class="mt-2 btn btn-sm btn-primary">Klik untuk melihat histori tugas</a>
							   </div>
						   </div>
					   </div>
			   </div>
				@endforeach
    </div>
</div>
@endsection
