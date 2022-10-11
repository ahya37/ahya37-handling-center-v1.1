<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PetugasModel;
use App\AktivitasUmrahPetugasModel;
use App\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PetugasController extends Controller
{
    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {       
        DB::beginTransaction();
        try {
            
            $request->validate([
                    'email' => 'required|email|unique:users|max:255',
                ]);

            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? '12345678' : $request->password;
     
            $user = User::create([
                 'name' => $request->name,
                 'email' => $request->email,
                 'password' => Hash::make($password),
                 'aps_level_id' => 3
             ]);
     
              PetugasModel::create([
                 'user_id' => $user->id,
                 'nama' => $request->name,
                 'create_by' => Auth::user()->id
             ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }
        return redirect()->route('pembimbing.create')->with(['success' => 'Petugas berhasil disimpan']);

    }

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
        $petugas    = PetugasModel::where('id', $id)->first();
        $user       = User::where('id', $petugas->user_id)->first();
        return view('petugas.edit', compact('petugas','user'));
    }

    public function update(Request $request, $id)
    {
        
        DB::beginTransaction();
        try {

            $request->validate([
                    'email' => 'required|email|max:255',
            ]);

            $petugas = PetugasModel::where('id', $id)->first();

            $userModel  = new User();
            $user       = $userModel->where('id', $petugas->user_id)->first();

            // cek email
            $cek_email = $userModel->where('email', $request->email)
                        ->where('id','!=',$petugas->user_id)->count();

            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? $user->password : Hash::make($request->password);
            
            if($cek_email == 0){
                $user->update([
                     'name' => $request->name,
                     'email' => $request->email,
                     'password' => $password,
                 ]);

                  $petugas->update([
                     'nama' => $request->name,
                 ]);

                DB::commit();
                return redirect()->route('pembimbing.index')->with(['success' => 'Petugas berhasil diubah']);

            }else{
                return redirect()->back()->with(['warning' => 'Email sudah terdaftar']);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }


    }
    
    public function listData()
    {
        $pembimbing = PetugasModel::select('id','nama')->where('isdelete',0)->orderBy('nama','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($pembimbing)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('petugas.edit', $item->id).'" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a> 
                        <button onclick="onDeletePetugas(this)" id="'.$item->id.'" value="'.$item->nama.'" class="btn btn-sm fa fa-trash text-danger" title="Hapus"></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;
            
            // cek ke aktivitas umrah apakah id ini masih active
            $aktivitasUmrah = AktivitasUmrahPetugasModel::where('petugas_id', $id)
                            ->where('status','active')->count();
            // jika aktif tidak bisa di hapus
            // hanya jika sudah finsh boleh di hapus
            if($aktivitasUmrah == 0){
                $umrah = PetugasModel::where('id', $id)->first();
                $umrah->delete();
                $user = User::where('id', $umrah->user_id)->first();
                $user->delete();
            }else{
                return ResponseFormatter::error([
                   null,
                   'message' => ''
                ]); 
            }
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus tugas'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function getDataPetugas(Request $request)
    {

            $data = PetugasModel::select('id','nama')->where('isdelete',0)->get();
           
            if($request->has('q')){
            $search = $request->q;

            $data = PetugasModel::select("id","nama")

            		->where('nama','LIKE',"%$search%")

            		->get();

        }
        return response()->json($data);

    }
}
