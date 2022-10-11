@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
				@include('layouts.back-button')

				<h6 class="mb-0">Jawaban Responden</h6>
				<h6 class="mb-0">Nama : {{ $responden->nama }}</h6>
				<hr/>
				<div class="card radius-10 ">
                    <div class="card-body">
                            <div class="table">
							<table id="listData" class="table table-hover table-border" style="width:100%">
								<thead>
									<tr>
                                        <th>No</th>
                                        <th>Pertanyaan</th>
                                        <th>Pilihan</th>
                                        <th>Jawaban</th>
									</tr>
								</thead>
								<tbody>
                                   @foreach ($data as $row)
                                       <tr>
                                           <td>{{ $no++ }}</td>
                                           <td class="col-5">{{ $row['pertanyaan'] }}</td>
                                           <td>
                                               @foreach ($row['pilihan'] as $item)
                                               @if ($item->isi != '')
                                               <p>({{ $item->nomor }}) {{ $item->isi }},</p>
                                               @endif
                                               @endforeach
                                           </td>
                                           <td>{{ $row['jawaban'] }}</td>
                                       </tr>
                                   @endforeach
                                </tbody>
							</table>
						</div>       
                    </div>
                 </div>	
                 
                 <div class="card radius-10">
                     <div class="card-body">
                            <div class="table">
							<table id="listData" class="table table-hover table-border" style="width:100%">
								<thead>
									<tr>
                                        <th>Pertanyaan</th>
                                        <th>Jawaban</th>
									</tr>
								</thead>
								<tbody>
                                   @foreach ($essay as $row)
                                       <tr>
                                           <td class="col-5">{{ $row->isi }}</td>
                                           <td class="col-5">{{ $row->essay }}</td>
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
