<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MuthowwifModel extends Model
{
    protected $table = 'muthowwif';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function getDataTableListData()
    {
       $sql = DB::table('aktivitas_umrah_muthowwif as a')
            ->select('a.id','b.nama as muthowwif','c.tourcode', 'a.status','c.dates','c.id as umrah_id','c.start_date','c.end_date','a.status_tugas',
            DB::raw('
                (
                    select sum(nilai_akhir) from detail_aktivitas_umrah_muthowwif where aktivitas_umrah_id = a.id) as nilai_akhir'
                )
            )
            ->join('muthowwif as b','b.id','=','a.muthowwif_id')
            ->join('umrah as c','c.id','=','a.umrah_id')
            ->where('a.isdelete', 0);

        return $sql;
    }

    public static function getDataMuthowwifUmrahByMonth($month, $year)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
            ->select('b.id','b.nama')
            ->join('muthowwif as b','a.pmuthowwif_id','=','b.id')
            ->whereMonth('a.created_at', $month)
            ->whereYear('a.created_at', $year)
            ->groupBy('b.id','b.nama')
            ->get();

        return $sql;
    }

    public static function getDataMuthowwifUmrahByMonthAndSearch($month, $year, $search)
    {
        $sql = DB::table('aktivitas_umrah_muthowwif as a')
                ->select('b.id','b.nama')
                ->join('muthowwif as b','a.muthowwif_id','=','b.id')
                ->whereMonth('a.created_at', $month)
                ->whereYear('a.created_at', $year)
                ->where('b.nama','LIKE',"%$search%")
                ->groupBy('b.id','b.nama')
                ->get();

        return $sql;
    }
}
