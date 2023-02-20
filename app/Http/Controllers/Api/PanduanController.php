<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\PanduanModel;

class PanduanController extends Controller
{
    public function listPanduan()
    {
        try {

            $panduan = PanduanModel::select('id','judul','slug')->get();
            return ResponseFormatter::success([
                'message' => 'List panduan',
                'panduan' => $panduan
            ],200);

        }catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],500);
        }
        
    }

    public function readPanduan()
    {
        try {

            $id = request('id');
            $slug = request('slug');

            $panduan = PanduanModel::select('id','judul','desc')->where('id', $id)->first();
            return ResponseFormatter::success([
                'message' => 'List panduan',
                'panduan' => $panduan
            ],200);

        }catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],500);
        }
        
    }
}
