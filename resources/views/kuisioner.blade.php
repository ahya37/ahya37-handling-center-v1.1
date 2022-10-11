<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--favicon-->
			<link rel="icon" href="/assets/images/iclogo.png" type="image/png" />

		<!-- loader-->
		<link href="/assets/css/pace.min.css" rel="stylesheet" />
		<script src="/assets/js/pace.min.js"></script>
		<!-- Bootstrap CSS -->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/css/app.css" rel="stylesheet">
		<link href="/assets/css/icons.css" rel="stylesheet">
		<title>{{ $kuisioner->label }}</title>
	</head>

<body>
	<!--wrapper-->
    <div class="wrapper">
        <div class="mt-3">
            <div class="container-fluid">
                <div class="row-cols-lg-2">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            {{-- @if ($kuisioner->kuisioner_id == 1)
                                <img src="https://lh6.googleusercontent.com/-bQ05Ww5VgQfEE3eGYO6z8tytd_QK6SQ1q3il5BSEXHCdI52oTBnEh8Hhj15q6dCb8izx-p81q-ckkly06KyuKVrd2W6n0nH0EvwJ7lt52fza84I98I-bscabm1mipbO=w800"  class="" alt="" class="rounded" width="600" />
                            @else
                                <img src="https://lh3.googleusercontent.com/NulNWEk_6vH8TuIr2eCYmkWFI4b1yNXw8CE55YgDnQC1YfX3pe9FyDkssjSEmUn3kK_T_blRF6DsRdOaTcun9ak3dcrCP5-I6NSlv2vN2CtBhZ5D3004xJjUj8R-lV7b=w1024"  alt="" class="rounded" width="600" />
                                @endif --}}
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset('assets/images/logo-PERCIK-PNG-1.png') }}"  alt="" class="rounded"  width="300"/>
                                    </div>
                                </div>
                        </div>
                        <div class="card ">
                            <div class="card-body rounded">
                                <div class="rounded">
                                    <h3 class="text-center">{{ $kuisioner->label }}</h3>
                                    <p>
                                        Untuk meningkatkan pelayanan kepada jama’ah dengan lebih baik lagi kedepannya, kami mohon keikhlasan jama’ah sekalian untuk meluangkan waktu mengisi kuisioner di bawah ini. Pilihlah jawaban yang cocok sesuai dengan hati nurani Bapak/Ibu.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('kuisioner.umrah.save', ['kuisionerumrah_id' => $kuisioner->id,'umrah_id' => $kuisioner->umrah_id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                             <div class="card ">
                                <div class="card-body rounded">
                                    <div class="rounded">
                                         <div class="col-md-12">
                                                <label class="form-label">Nama</label>
                                                <input type="text" name="nama" class="form-control @error ('name') is-invalid @enderror" required placeholder="Jawaban Anda">
                                                @error('name')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card ">
                                <div class="card-body rounded">
                                    <div class="rounded">
                                        <div class="col-md-12">
                                                <label class="form-label">Identitas Anda</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="jk" type="radio" value="Laki-laki" required> Laki-laki
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="jk" type="radio" value="Perepmuan" required> Perempuan
                                                </div>
                                                @error('name')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card ">
                                <div class="card-body rounded">
                                    <div class="rounded">
                                         <div class="col-md-12">
                                                <label class="form-label">Usia</label>
                                                <input type="text" name="usia" class="form-control @error ('usia') is-invalid @enderror" required placeholder="Jawaban Anda">
                                                @error('usia')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach ($pertanyaan as $item)
                                <div class="card ">
                                <div class="card-body rounded">
                                    <div class="rounded">
                                         <div class="col-md-12">
                                                <label class="form-label">{{ $no++ }}. {{ $item->isi }}</label>
                                            @php
                                                $pertanyaan_id = $item->id;
                                                $pilihan       = $pilihanModel->where('pertanyaan_id',$pertanyaan_id)->get();
                                            @endphp
                                            @foreach ($pilihan as $val)
                                                <div class="form-check">
                                                    <input class="form-check-input" required name="jawaban[{{ $val->pertanyaan_id }}]" type="radio" value="{{ $val->id }}" > {{ $val->isi }}
                                                </div>                                             
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @foreach ($essay as $item)
                                <div class="card ">
                                <div class="card-body rounded">
                                    <div class="rounded">
                                         <div class="col-md-12">
                                                <label class="form-label">{{ $item->isi }}</label>
                                            @php
                                                $pertanyaan_id = $item->id;
                                                $pilihan       = $pilihanModel->where('pertanyaan_id',$pertanyaan_id)->get();
                                            @endphp
                                            @foreach ($pilihan as $val)
                                            <textarea name="essay[{{ $val->pertanyaan_id }}]" class="form-control"></textarea>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div class="col-md-12 mb-4">
                                <button class="btn btn-sm btn-primary" type="submit">Kirim</button>
                            </div>
                            <div class="col-md-12 mb-4">
                            </div>
                            <div class="col-md-12 mb-4">
                                <p class="text-center">Kuisioner © {{ date('Y') }}. Percik Tours</p>
                            </div>
                        </form>

                    </div>
                </div>
                <!--end row-->
                </div>
            </div>
        </div>
       
    </div>


	<!--end wrapper-->

	<!--plugins-->
	<script src="/assets/js/jquery.min.js"></script>
</body>
</html>
