<?php

namespace App\Http\Controllers;

use App\EssayJawabanKuisionerUmrahModel;
use App\JawabanKuisionerUmrahModel;
use App\PilihanModel;
use App\KuisionerUmrahModel;
use Illuminate\Http\Request;
use App\PertanyaanKuisionerModel;
use App\RespondenKuisionerUmrahModel;
use Illuminate\Support\Facades\DB;

class KuisionerUmrahController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function view($url)
    {
        DB::beginTransaction();
        try {
            // GET DATA KUISIONER UMRAH BERDASARKAN KODE URL
            $kuisioner = KuisionerUmrahModel::select('id','label','kuisioner_id','umrah_id')->where('url', $url)->first();
            $pertanyaanModel = new PertanyaanKuisionerModel(); 
            $pertanyaan = $pertanyaanModel->select('id','kuisioner_id','isi','required','nomor')
                                            ->where('nomor','!=',null)
                                            ->where('kuisioner_id', $kuisioner->kuisioner_id)->get();
            $essay = $pertanyaanModel->select('id','kuisioner_id','isi','required','nomor')
                                            ->where('nomor','=',null)
                                            ->where('kuisioner_id', $kuisioner->kuisioner_id)->get();
            $pilihanModel    = new PilihanModel();
            $no = 1;

        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return '<h1 style="text-align: center;">Oops ... Not Found</h1>';
        }
        return view('kuisioner', compact('kuisioner','pertanyaan','pilihanModel','no','essay'));
    }

    public function saveKuisionerUmrah(Request $request, $kuisionerumrah_id, $umrah_id)
    {
        DB::beginTransaction();
        
        try {

            // SIMPAN KE TB responden_kuisioner_umrah
            $respondenModel = new RespondenKuisionerUmrahModel(); 
            $responden  = $respondenModel->create([
                'kuisioner_umrah_id' => $kuisionerumrah_id,
                'nama' => strtoupper($request->nama),
                'jenis_kelamin' => $request->jk,
                'usia' => $request->usia
            ]);

            // MENGAMBIL NOMOR YANG DIPILIH DARI SETIAP JAWABAN PER PERTANYAAN
            $pilihan  = $request->jawaban;
            foreach ($pilihan as $key => $val) {
                $pilihan = PilihanModel::where('id', $val)->first();

                $jawaban = new JawabanKuisionerUmrahModel();
                $jawaban->responden_kuisioner_umrah_id = $responden->id;
                $jawaban->kuisioner_umrah_id = $kuisionerumrah_id;
                $jawaban->umrah_id = $umrah_id;
                $jawaban->pertanyaan_id = $pilihan->pertanyaan_id;
                $jawaban->pilihan_id = $pilihan->id;
                $jawaban->jawaban = $pilihan->nomor;
                $jawaban->save();
            }
            $essay = $request->essay;
            foreach ($essay as $key => $val) {
                $essayModel = new EssayJawabanKuisionerUmrahModel();
                $essayModel->responden_kuisioner_umrah_id = $responden->id;
                $essayModel->kuisioner_umrah_id = $kuisionerumrah_id;
                $essayModel->umrah_id = $umrah_id;
                $essayModel->pertanyaan_id = $key;
                $essayModel->essay = $val;
                $essayModel->save();
            }

            // HITUNG JUMLAH RESPONDEN DAN UPDATE
            $count_responden = $respondenModel->where('kuisioner_umrah_id', $kuisionerumrah_id)->count();
            $kuisionerUmrah  = KuisionerUmrahModel::where('id', $kuisionerumrah_id)->first();
            $kuisionerUmrah->update(['jumlah_responden' => $count_responden]);

        DB::commit();

        return redirect()->route('kuisioner.umrah.success')->with(['success' => 'Kuisioner sukses terkirim']);

        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
        
    }

    public function kuisionerSuccess()
    {
        return view('kuisioner-success');
    }
}
