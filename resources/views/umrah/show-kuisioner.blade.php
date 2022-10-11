@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
				@include('layouts.back-button')

				<h6 class="mb-0">Kuisioner Tourcode : {{ $kuisioner->tourcode }}</h6>
				<hr/>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card radius-10">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mt-0"> {{ $kuisioner->label }} </h5>
                                                </div>
                                            </div>
                                        </div>
                         </div>				
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-10">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 ms-2">
                                                     <h5 class="mt-0 text-center"> Jumlah Responden </h5>
                                                     <h5 class="mt-0 text-center"> {{ $kuisioner->jumlah_responden }} </h5>
                                                </div>
                                            </div>
                                        </div>
                         </div>				
                    </div>
                </div>
				<h6 class="mb-0">Responden</h6>
				<hr/>
				<div class="card radius-10">
                    <div class="card-body">
                            <div class="table-responsive">
							<table id="listData" class="table table-hover table-border" style="width:100%">
								<thead>
									<tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Usia</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Opsi</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach ($responden as $row)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row->nama }}</td>
                                        <td>{{ $row->usia }}</td>
                                        <td>{{ $row->jenis_kelamin }}</td>
                                        <td>
                                            <a href="{{ route('umrah.kuisioner.respondendetail', $row->id) }}" class="btn btn-sm btn-primary" >Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
							</table>
						</div>       
                    </div>
                 </div>				
    </div>
</div>


@endsection
@push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('js/show-kuisioner.js') }}"></script>
@endpush