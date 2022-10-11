<table>
    <tr>
        <th>
            <blockquote><strong>{{$sop->name}}</strong></blockquote>
        </th>
    </tr>
</table>
<table cellspacing='0' class="table" border="1">
    <tr>
        <th style="padding: 3"><strong>No</strong></th>
        <th align="left">
            <span style="margin-left: 2"><strong> TAHAPAN TUGAS</strong></span>
        </th>
        <th><span style="margin-left: 2; margin-right:2"><strong>UPLOAD FOTO</strong> </span></th>
        <th align="right"><span style="margin-left: 2; margin-right:2"><strong>NILAI</strong> </span></th>
    </tr>
    @foreach ($data as $item)
        <tr>
            <th colspan="3" align="left">
                <span style="margin-left: 15"><strong> {{ $item['nomor'] }} . {{ $item['nama'] }} </strong></span>
            </th>
        </tr>
        @foreach ($item['tugas'] as $row)
            <tr>
                <td>{{ $row->nomor }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->require_image == 'Y' ? 'Wajib' : 'Tidak Wajib' }}</td>
                <td align="right">
                    <span style="margin-right: 5">{{ $row->nilai_point }}</span>
                </td>
            </tr>
        @endforeach
    @endforeach
</table>
