@extends('layouts.app')
@push('styles')
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

                <h6 class="mb-0">Pertanyan Pilihan</h6>
				<hr/>
                <div class="row">
                @foreach ($result as $item)
                    <div class="col-md-6">
                        <div class="card radius-10 ">
                            <div class="card-header">({{ $item['nomor'] }}). {{ $item['pertanyaan'] }}</div>
                            <div class="card-body">
                                    <div class="table">
                                    <table id="listData" class="table table-hover table-border" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Pilihan</th>
                                                <th>Jumlah Jawaban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $pilihan = $pilihanModel->select('id','nomor','isi')->where('pertanyaan_id', $item['id'])->get();
                                            @endphp
                                            @foreach ($pilihan as $row)
                                                @php
                                                    $count_jawaban = $jawabanModel->where('pilihan_id', $row->id)->where('umrah_id', $umrah_id)->count();
                                                @endphp
                                                <tr>
                                                    <td>({{ $row->nomor }}).{{ $row->isi }}</td>
                                                    <td>{{ $count_jawaban }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>       
                            </div>
                        </div>	
                    </div>
                    @endforeach
                </div>

                <h6 class="mb-0">Pertanyan Essay</h6>
				<hr/>

                <div class="row">
                @foreach ($pertanyaan_essay as $item)
                    <div class="col-md-12">
                        <div class="card radius-10 ">
                            <div class="card-header">{{ $item->isi }}</div>
                            <div class="card-body">
                                    <div class="table">
                                    <table id="listData" class="table table-hover table-border" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jawaban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $essay = $jawabanEssayModel->select('essay')->where('pertanyaan_id', $item->id)->where('umrah_id', $umrah_id)->get();
                                                $no    = 1;
                                            @endphp
                                            @foreach ($essay as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row->essay }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>       
                            </div>
                        </div>	
                    </div>
                    @endforeach
                </div>

                <h6 class="mb-0">Persentasi</h6>
				<hr/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card radius-10 ">
                            <div class="card-body">
                                    <div class="table">
                                    <table id="listData" class="table table-hover table-border" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Jawaban</th>
                                                <th>Jumlah</th>
                                                <th>Persentasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($persentasi_jawaban as $row)
                                                <tr>
                                                    <td>{{ $row['pilihan'] }}</td>
                                                    <td>{{ $row['jumlah'] }}</td>
                                                    <td>{{ $row['persentage'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>       
                            </div>
                        </div>	
                    </div>
                </div>
    </div>
</div>


@endsection
