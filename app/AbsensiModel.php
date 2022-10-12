<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AbsensiModel extends Model
{
    public function getReportAbsenWhereDate($nik, $day)
    {
        $absensi = DB::table('time_attendance')
            ->select(
                DB::raw("DATE_FORMAT(attendance_date, '%d') as date"),
                DB::raw("DATE_FORMAT(clock_in, '%H:%i:%s') as jam_masuk"),
                DB::raw("DATE_FORMAT(clock_out, '%H:%i:%s') as jam_keluar"))
            ->where('nik', $nik)
            ->whereDate('attendance_date','=', $day)
            ->first();
        return $absensi;
    }
}
