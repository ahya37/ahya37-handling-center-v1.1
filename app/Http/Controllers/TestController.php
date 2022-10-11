<?php

namespace App\Http\Controllers;

use App\PilihanModel;
use App\AbsensiModel;
use App\AktivitasUmrahModel;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Excel;
use App\Exports\AbsensiExportExcel;

class TestController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function updatePilihan()
    {
        $pilihan = PilihanModel::all();

        foreach ($pilihan as $key => $value) {
            $isi      = $value->isi;

            switch ($isi) {
                case 'Cepat':
                    $kategori = 1;
                    break;
                case 'Memuaskan':
                    $kategori = 2;
                    break;
                case 'Enak':
                    $kategori = 3;
                    break;
                case 'Ya':
                    $kategori = 4;
                    break;
                case 'Ya':
                    $kategori = 5;
                    break;
                case 'Ya':
                    $kategori = 6;
                    break;


                default:
                    # code...
                    break;
            }
            $update = PilihanModel::where('id', $value->id)->first();
            $update->update(['kategori_pilihan_jawaban_id']);
        }

    }

    public function testListTugas($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data = $aktitivitasModel->getListTugasByAktivitasUmrahIdTest($id);
        return $data;
    }

}
