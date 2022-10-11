<html>
    <head>
        <title>{{$sop->name}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }
            header {
                position:absolute;
                top: -100px;
                left: 0px;
                right: 0px;
                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: center;
                line-height: 30px;
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
            font-family: Helvetica;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 2px solid;
            width: 100%;
            margin-top: 10px;
            }
            .table th {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #D35400;
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
        <img
        src="{{asset('/assets/images/logo-PERCIK-PNG-com.png')}}"
        alt="logo icon"
        width="150"
        style="margin-top: 5"
      />
            <blockquote><strong>{{$sop->name}}</strong></blockquote>
    </header>
        <section align="justify">
            <table cellspacing='0' class="table">
                    <tr>
                        <th style="padding: 3">NO</th>
                        <th align="left">
                            <span style="margin-left: 2">TAHAPAN TUGAS</span>
                        </th>
                        <th><span style="margin-left: 2; margin-right:2">NILAI</span></th>
                    </tr>
                    @foreach ($data as $item)
                    <tr>
                        <th colspan="3" align="left">
                            <span style="margin-left: 15">{{$item['nomor']}} . {{$item['nama']}}</span>
                        </th>
                    </tr>
                    @foreach ($item['tugas'] as $row)
                    <tr>
                        <td>{{$row->nomor}}</td>
                        <td>{{$row->nama}}</td>
                        <td align="right">
                            <span style="margin-right: 5">{{$row->nilai_point}}</span>
                        </td>
                    </tr>
                    @endforeach
                    {{-- <tr>
                        <td>2</td>
                        <td>Menerima data dari management berupa : Itinerary program umrah, daftar identitas jamaah, pelaksanaan manasik, dan keberangkatan.</td>
                    </tr> --}}

                    @endforeach
            </table>
        </section>
        
         <footer>
             <small>
                <i>
                    Percik Tours,  {{date('Y')}}
                </i> 
             </small>
        </footer>
</body>
</html>