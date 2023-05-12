<html>
    <head>
        <title>KRITIK SARAN {{ strtoupper($data['kuisionerUmrah'])}} - {{ $data['tourcode'] }}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }
            header {
                position: fixed;
                top: -100px;
                left: 0px;
                right: 0px;
                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: center;
                line-height: 35px;
            }
            footer {
                position: fixed; 
                bottom: -100px; 
                left: 0px; 
                right: 0px;
                height: 100px; 
                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: right;
                line-height: 90px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            .table {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
            width: 100%
            }
            .table th {
            font-size: 12px;
            padding: 5px auto;
            border-left:2px solid #e0e0e0;
            border-right:2px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            .table td {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000;
            /* text-align: center; */
            padding-left: 5px;
            padding-right: 5px;
            }
            .guide{
                margin-bottom: 10px;
            }
            .alasan {
                width: 30%; !important
            }
            .tahapan {
                width: 50%; !important
            }
            .pelaksanaan {
                width: 10%; !important
            }
            .no {
                width: 5%; !important;
            }
        </style>
    
<body>
    <header>
        <h4>
            KRITIK SARAN {{ strtoupper($data['kuisionerUmrah']) }} - {{$data['tourcode'] }}
        </h4> 
    </header>
        <section align="justify">
            <table class="guide">
                <tr>
                    <td>PEMBIMBING :</td>
                </tr>
                <tr>
                    <td>
                        @php
                            $nop = 1;
                        @endphp
                        @foreach ($data['pembimbing'] as $item)
                            <span>({{ $nop++ }}). {{ $item->nama }} ({{ $item->status_tugas }})</span><br>
                        @endforeach
                    </td>
                </tr>
            </table>

            <span>Pertanyaan : </span><br>
            <span>{{ $data['pertanyaan'] }}</span>
            @if ($data['typepertanyaan'] === 'umum')
                <table cellspacing='0' class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>JAWABAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data['jawaban_essay'] as $item)
                        <tr>
                            <td>{{ $data['no']++ }}</td>
                            <td>{{ $item->essay }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
            <table cellspacing='0' class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>JAWABAN</th>
                        <th>DARI</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data['jawaban_essay'] as $item)
                    <tr>
                        <td>{{ $data['no']++ }}</td>
                        <td>{{ $item->essay }}</td>
                        <td>{{ $item->responden }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </section>
        
         <footer>
             <small>
                <i>
                    Dicetak Oleh : {{ Auth::user()->name }}, Tanggal : {{ date('d-m-Y') }}
                </i> 
             </small>
        </footer>
</body>
</html>