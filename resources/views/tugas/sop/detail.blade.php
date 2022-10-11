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

				<h6 class="mb-0 ">Detail SOP</h6>
				<input type="hidden" id="userId" value="{{$user_id}}">
				<hr/>
				<div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
											<div class="row">
												<div class="col-md-8">
													<h6 class="mt-0">{{ $sop->name  }}</h6>
												</div>
												<div class="col-md-4">
													<button type="button" class="btn btn-sm text-primary" title="Edit" onclick="onUpdateSOP(this)" data-id="{{$sop->id}}" data-status="edit" data-name="{{ $sop->name  }}"><i class="fa fa-edit"></i>Edit</button>
													<button type="button" class="btn btn-sm text-primary" onclick="onUpdateSOP(this)" data-status="add" data-id="{{$sop->id}}" data-name="{{ $sop->name  }}"><i class="lni lni-circle-plus" ></i> Tambah Judul Tugas</button>
												</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
				<hr/>
				<div class="row">
					<h6 class="mb-3">Daftar Judul Tugas :</h6>
					@foreach ($result_judul as $item)

					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-8">
										{{$item['nomor']}} . {{$item['nama']}}
									</div>
									<div class="col-4">
										<button type="submit" class="btn btn-sm text-primary" title="Edit" data-id="{{$item['id']}}" onclick="onUpdateJudul(this)" data-status="edit" data-name="{{ $item['nama']  }}" data-nomor="{{ $item['nomor']  }}"><i class="fa fa-edit"></i>Edit Judul</button>
										<button type="submit" class="btn btn-sm text-primary" data-id="{{$item['id']}}" onclick="onUpdateJudul(this)" data-status="add" data-name="{{ $item['nama']  }}" data-nomor="{{ $item['nomor']  }}" data-sopid="{{$sop->id}}"><i class="lni lni-circle-plus"></i> Tambah Tugas</button>
									</div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-hover display" style="width: 100%">
									<thead>
										<th>No</th>
										<th class="col-5">Tugas</th>
										<th class="col-4">Nilai</th>
										<th class="col-4">Upload Foto</th>
										<th class="col-5">Opsi</th>
									</thead>
									<tbody>
										@foreach ($item['tugas'] as $tugas)
										<tr>
											<td>{{$tugas->nomor}}</td>
											<td>{{$tugas->nama}}</td>
											<td>{{$tugas->nilai_point}}</td>
											<td>
												<input type="radio" name="required_foto{{$tugas->id}}" data-type="image" data-id="{{$tugas->id}}" value="Y" onclick="settingUploadFileSop(this)" {{$tugas->require_image == 'Y' ? 'checked' : ''}} />Wajib
												<input type="radio" name="required_foto{{$tugas->id}}" data-type="image" data-id="{{$tugas->id}}" value="N" onclick="settingUploadFileSop(this)" {{$tugas->require_image == 'N' ? 'checked' : ''}} />Tidak
											</td>
											<td>
												<button type="submit" class="btn btn-sm text-primary fa fa-edit" title="Edit" data-id="{{$tugas->id}}" onclick="updateTugas(this)" data-name="{{ $tugas->nama  }}" data-nomor="{{ $tugas->nomor }}" data-nilai="{{ $tugas->nilai_point }}"></button>
												<button type="submit" class="btn btn-sm text-danger fa fa-trash" title="Edit" data-id="{{$tugas->id}}" onclick="onDelete(this)" data-name="{{ $tugas->nama  }}" data-nomor="{{ $tugas->nomor }}" data-nilai="{{ $tugas->nilai_point }}"></button>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
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
<script src="{{ asset('sweetalert2/dist/new/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/sop-detail.js') }}"></script>
@endpush