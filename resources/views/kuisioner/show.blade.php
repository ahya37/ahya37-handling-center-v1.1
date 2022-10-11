@extends('layouts.app')

@section('content')
<div class="page-wrapper">
        <div class="page-content">
				<h6 class="mb-0">Hasil Kuisioner</h6>
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
                                            @foreach ($item['pilihan'] as $pilihan)
                                            <tr>
                                                <td>{{ $pilihan->pilihan }}</td>
                                                <td>{{ $pilihan->total_jawaban }}</td>
                                            </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td>8</td>
                                                <td>3</td>
                                                <td>6</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>       
                            </div>
                        </div>	
                    </div>
                    @endforeach
                </div>

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
                                                $essay = $jawabanEssayModel->select('essay')->where('pertanyaan_id', $item->id)->get();
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
    </div>
</div>


@endsection
