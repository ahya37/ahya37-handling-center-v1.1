<html>

<head>
    <title>LAPORAN BIMBINGAN UMRAH - </title>
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
        /* background: #eaebec; */
        border: #ccc 1px solid;
        width: 50%;
        margin-left: auto;
        margin-right: auto;
    }

    .table th {
        font-size: 12px;
        padding: 3px auto;
        border-left: 1px solid #e0e0e0;
        border-bottom: px solid #e0e0e0;
        background: #fff;
        color: #000;
    }

    .table td {
        font-size: 12px;
        padding: 2px;
        border-left: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        color: #000;
        padding-left: 5px;
        text-align: center
    }

    .guide {
        margin-bottom: 10px;
    }

    .alasan {
        width: 30%;
         !important
    }

    .tahapan {
        width: 50%;
         !important
    }

    .pelaksanaan {
        width: 10%;
         !important
    }

    .no {
        width: 5%;
         !important;
    }.
    .font{
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS';
    }

    .tablename {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000;
        /* background: #eaebec; */
        border: 0;
        width: 50%;
        margin-left: auto;
        margin-right: auto;
    }

    .cs-header {
        margin-left: auto;
        margin-right: auto;
    }
</style>

<body>
    <header>
        <h5 class="font cs-header">
            BERITA ACARA STOK OPNAME PERSEDIAAN PERLENGKAPAN
        </h5>
    </header>
    <section >
        <blockquote style="text-align: center; margin-top:-20px">Melakukan pemeriksaan Stok opname persediaan perlengkapan Pada Tanggal {{ date('d-M-Y') }} Di Kantor Percik Tours jalan arcamanik endah no 101 bandung.</blockquote>
    </section>
    <br>
    <br>
    <section align="justify">
        <table class="tablename">
            <tr>
                <td>Nama Lengkap </td><td>:</td><td> Nina Nurlina</td>
            </tr>
            <tr>
                <td>Jabatan </td><td>:</td><td> Staff Accounting</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Nama Lengkap </td><td>:</td><td> Hendi Suhendi</td>
            </tr>
            <tr>
                <td>Jabatan </td><td>:</td><td> Staff Operasional</td>
            </tr>
        </table>
        <br>
        <br>
        <section >
            <blockquote style="text-align: center; margin-top:-20px">Dengan rincian persediaan perlengkapan sebagai berikut :</blockquote>
        </section>
        <table cellspacing='0' class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>JENIS BARANG</th>
                    <th>QTY</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ ucwords($item->it_name)}}</td>
                    <td>{{ $item->ic_count }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td>{{ $total }}</td>
                </tr>
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
