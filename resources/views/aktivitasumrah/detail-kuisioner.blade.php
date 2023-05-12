@extends('layouts.app')
@push('styles')
{{-- <link rel="stylesheet" href="{{asset('/vendor/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('vendor/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('vendor/font-awesome/all.min.css')}}" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" /> --}}

@endpush
@section('content')
<div class="page-wrapper">
        <div class="page-content">
        @include('layouts.message')
				<h6 class="mb-0 ">Kuisioner Pembimbing Umrah</h6>
				<hr/>
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<h6><b>{{$kuisioner->label}}</b></h6>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2"><h6>Tourcode</h6></div>
										<div class="col-md-10">
											<h6 class="text-success">: {{$kuisioner->tourcode}}</h6>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2"><h6>Pembimbing</h6></div>
										<div class="col-md-10">
											<ol>
												@php
													$no_pemb = 1;
												@endphp
												@foreach ($aktivitas as $item)
													<li>{{$no_pemb++}}. {{$item->nama}} ({{$item->status_tugas}})</li>
												@endforeach
											</ol>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-8"><h6>Jumlah Jama'ah</h6></div>
										<div class="col-md-2">
											<h6 class="text-success">: <b>{{$kuisioner->count_jamaah}}</b></h6>
										</div>
										<div class="col-md-8"><h6>Jumlah Responden</h6></div>
										<div class="col-md-2">
											<h6 class="text-success">: <b>{{$kuisioner->jumlah_responden}}</b></h6>
										</div>
										<div class="col-md-8"><h6>Persentasi</h6></div>
										<div class="col-md-2">
											@php
												$persentage = ($kuisioner->jumlah_responden/$kuisioner->count_jamaah)*100;
											@endphp
											<h6 class="text-success">: <b>{{round($persentage) }} %</b></h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					@php
						$no_pertanyaan = 1;
					@endphp
					@foreach ($result_kuisioner as $item)
					<div class="col-md-8">
						<div class="card">
							<div class="card-header">
								<b>{{$item['nomor']}}. {{$item['isi']}}</b>
							</div>
							<div class="card-body">
								@php
									$no_jawaban = 1;
									$no_jawaban_rumus = 1;
								@endphp
								{{-- <div class="row">
									<div class="col-md-2">{{$no_jawaban++}}. {{$val->jawaban}}</div>
									<div class="col-md-2"><span>{{$val->jml_jawaban}}</span></div>
								</div> --}}
								<table class="table" style="width: 100%">
									<tr>
										<th>No</th>
										<th >Jawaban</th>
										<th style="text-align: right">Jumlah</th>
										<th style="text-align: right">Rata-Rata</th>
									</tr>
									@foreach ($item['jawaban'] as $val)
									
									@php
										$avg = ($val->jml_jawaban/$kuisioner->jumlah_responden)*100;
										$r_persentage   = $gf->generateNilaiKuisioner($no_jawaban_rumus++);
										$n_avg = ceil($avg);	
										$result_nilai = ceil(($n_avg*$r_persentage) / 100);

									@endphp
									
									<tr>
										<td>{{$no_jawaban++}}</td>
										<td>{{$val->jawaban}}</td>
										<td align="right">{{$val->jml_jawaban}}</td>
										<td align="right">{{$n_avg}}</td>
										{{-- <td style="text-align: right">{{$result_nilai}}</td> --}}
									</tr>
									@endforeach
									<tr>
										<td colspan="3"><b>Nilai</b></td>
										<td style="text-align: right"><b>{{$item['nilai']}}</b></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					@endforeach
				</div>

				<div class="row mt-4"></div>

				@foreach ($result_kuisioner_essay as $item)
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							@php
								$no_essay = 1;
								$no_essay_jawaban = 1;
							@endphp
								<div class="card-header"><b>{{$no_essay++}}. {{$item['isi']}}</b>
									<a href="{{ route('aktivitas.kuisioner.detail.kritiksaranpdf', ['umrahid' => $umrah_id,'kuisionerumrahid' => $kuisioner_umrah_id,'prtanyaanid' => $item['id']]) }}" class="btn btn-sm btn-primary float-right">Download PDF</a>
								</div>
								<div  class="card-body">
										<table class="table">
											<tr>
												<th>No</th>
												<th>Jawaban</th>
											</tr>
											@foreach ($item['jawaban'] as $val)
											<tr>
												<td>{{$no_essay_jawaban++}}</td>
												<td>{{$val->essay}}</td>
											</tr>
											@endforeach
										</table>

								</div>
						</div>
					</div>
				</div>
				@endforeach

    </div>
</div>
@endsection
{{-- @push('scripts')

<script src="{{asset('/vendor/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/vendor/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="{{asset('vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{{ asset('/js/activitas-umrah.js') }}"></script>
@endpush --}}