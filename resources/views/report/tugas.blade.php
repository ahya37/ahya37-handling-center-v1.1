<html>
    <head>
        <title>LAPORAN BIMBINGAN UMRAH - {{ $jadwal->tourcode }}</title>
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
            border-left:1px solid #e0e0e0;
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
            padding-left: 5px;
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
            LAPORAN BIMBINGAN UMRAH
        </h5> 
    </header>
        <section align="justify">
            <table cellspacing='0' class="guide">
                <tr>
                    <td>NAMA PEMBINGBING</td><td>:</td><td>{{ strtoupper($jadwal->pembimbing) }}</td>
                </tr>
                <tr>
                    <td>TOURCODE</td><td>:</td><td>{{ $jadwal->tourcode }}</td>
                </tr>
            </table>
            <table cellspacing='0' class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TAHAPAN TUGAS</th>
                        <th>PELAKSANAAN</th>
                        <th>ALASAN TIDAK DILAKSANAKAN</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($data as $row)
                       <tr>
                           <td class="no" align="center">{{ $row->nomor }}</td>
                           <td class="tahapan">{{ $row->nama }}</td>
                           <td class="pelaksanaan" align="center">{{ $row->status == 'Y' ? 'Ya' : 'Tidak' }}</td>
                           <td>{{ $row->alasan }}</td>
                       </tr>
                   @endforeach
                </tbody>
            </table>
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