@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
                {{-- <x:notify-messages /> --}}
				@include('layouts.message')
					<h6 class="mb-0 "><b>Nilai Bimbingan Umrah : {{$responden->tourcode}}</b></h6>
					<h6><b>Bersasarkan Kuisioner Jamaah</b></h6>
					<h6><b>Pembimbing Ibadah : </b></h6>
					@foreach ($pembimbing as $item)
						<h6><b>UST.{{$item->nama}}</b> ({{$item->status_tugas}})</h6>
					@endforeach
				<hr/>

				<span class="mb-4"><i>Total Jamaah  = {{$responden->count_jamaah}}, Data yang masuk  = {{$responden->jumlah_responden}}</i></span>

				<br>

				<div class="row">
					<div class="col-md-8 mt-4">
						@php $noJudul = 1; @endphp
						@foreach ($result_data as $kategori)
						<div class="card">
							<div class="card-header"><b>{{ $noJudul++ }}. {{$kategori['kategori']}}</b></div>
							<div class="card-body">
								<table class="table">
									 <tr>
										<th class="col">No</th>
										<th class="col">Pertanyaan</th>
									</tr> 
									 @php $no = 1; @endphp
									@foreach ($kategori['pertanyaan'] as $item)
									<tr>
										<td>{{ $no++ }}</td>
										<td>{{ $item->isi}}</td>
								   </tr>
									@endforeach
								</table>
							</div>
						</div>
						@endforeach
					</div>

    </div>
</div>
@endsection

@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
{{-- <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
{{-- <script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script> --}}
{{-- <script src="{{ asset('/js/detail-kuisioner-dashboard.js') }}"></script> --}}
{{-- <script>
	$('#tablePlace').DataTable();
</script> --}}
@endpush