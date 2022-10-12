<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AbsensiExportExcel implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $absensiModel;

    public function __construct($data, $absensiModel)
    {
        $this->data = $data;
        $this->absensiModel = $absensiModel;
    }

    public function view() : View
    {
        $data = $this->data;
        $absensiModel = $this->absensiModel;
        return view('report.absensi', ['data' => $data,'absensiModel' => $absensiModel]);
    }

    // public function collection()
    // {
    //     //
    // }
}
