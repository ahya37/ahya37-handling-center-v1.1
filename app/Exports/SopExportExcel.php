<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\TugasModel;

class SopExportExcel implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data;
    private $sop;

    public function __construct($data, $sop)
    {
        $this->data = $data;
        $this->sop = $sop;
    }

    public function view() : View
    {
        $data = $this->data;
        $sop = $this->sop;
        return view('report.excelsop', ['data' => $data, 'sop' => $sop]);
    }
    // use Exportable;


    // public function collection()
    // {
    //    $data = $this->data;
    //    $result = [];
    //    foreach ($data as $value) {
    //     $tugas = TugasModel::select('nomor','nama','nilai_point')->where('master_judul_tugas_id', $value->id)->get();
    //     $result[] = [
    //         'nomor' => $value->nomor,
    //         'nama' => $value->nama,
    //         'tugas' => $tugas
    //     ];
    //    }
    //    return collect($result);  
    // }

    // public function headings(): array
    // {
    //     return [
    //         'NOMOR',
    //         'NAMA',
    //         'TUGAS'
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $event->sheet->getStyle('A1:C1')->applyFromArray([
    //                 'font' => [
    //                     'bold' => true
    //                 ]
    //             ]);

    //         }
    //     ];
    // }
}
