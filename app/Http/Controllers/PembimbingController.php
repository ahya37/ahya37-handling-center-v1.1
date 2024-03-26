<?php

namespace App\Http\Controllers;

use App\User;
use App\PembimbingModel;
use App\AktivitasUmrahModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PembimbingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pembimbing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pembimbing.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
     
              PembimbingModel::create([
                 'user_id' => $user->id,
                 'nama' => $request->name,
                 'status' => 'PEMBIMBING',
                 'expired_passport' => date('Y-m-d', strtotime($request->expired_passport)),
                 'create_by' => Auth::user()->id
             ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }
        return redirect()->route('pembimbing.create')->with(['success' => 'Pembimbing berhasil disimpan']);

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
        $pembimbing = PembimbingModel::where('id', $id)->first();
        $user       = User::where('id', $pembimbing->user_id)->first();
        return view('pembimbing.edit', compact('pembimbing','user'));
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
        
        DB::beginTransaction();
        try {

            $request->validate([
                    'email' => 'required|email|max:255',
            ]);

            $pembimbing = PembimbingModel::where('id', $id)->first();

            $userModel  = new User();
            $user       = $userModel->where('id', $pembimbing->user_id)->first();

            // cek email
            $cek_email = $userModel->where('email', $request->email)
                        ->where('id','!=',$pembimbing->user_id)->count();

            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? $user->password : Hash::make($request->password);
            
            if($cek_email == 0){
                $user->update([
                     'name' => $request->name,
                     'email' => $request->email,
                     'password' => $password,
                 ]);

                  $pembimbing->update([
                     'nama' => $request->name,
                     'expired_passport' => date('Y-m-d', strtotime($request->expired_passport)),
                 ]);
            }else{
                return redirect()->back()->with(['warning' => 'Email sudah terdaftar']);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('pembimbing.index')->with(['success' => 'Pembimbing berhasil diubah']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;
            
            // cek ke aktivitas umrah apakah id ini masih active
            $aktivitasUmrah = AktivitasUmrahModel::where('pembimbing_id', $id)
                            ->where('status','active')->count();
            // jika aktif tidak bisa di hapus
            // hanya jika sudah finsh boleh di hapus
            if($aktivitasUmrah == 0){
                $umrah = PembimbingModel::where('id', $id)->first();
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

    public function listData()
    {
        $pembimbing = PembimbingModel::select('id','nama')->where('isdelete',0)->orderBy('nama','asc')->get();

        if (request()->ajax()) 
        {
            return DataTables::of($pembimbing)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('pembimbing.edit', $item->id).'" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a> 
                        <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nama.'" class="btn btn-sm fa fa-trash text-danger" title="Hapus"></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function getDataPembimbing(Request $request)
    {

            $data = PembimbingModel::select('id','nama')->where('isdelete',0)->get();
           
            if($request->has('q')){
            $search = $request->q;

            $data = PembimbingModel::select("id","nama")

            		->where('nama','LIKE',"%$search%")

            		->get();

        }
        return response()->json($data);

    }

    public function getDataPembimbingUmrahByMonth(Request $request, $month, $year)
    {
        try {

            $data = DB::table('aktivitas_umrah as a')
                    ->join('pembimbing as b','a.pembimbing_id','=','b.id')
                    ->select('b.id','b.nama')
                    ->whereMonth('a.created_at', $month)
                    ->whereYear('a.created_at', $year)
                    ->groupBy('b.id','b.nama')
                    ->get();

            if($request->has('q')){
                $search = $request->q;
                $data = DB::table('aktivitas_umrah as a')
                    ->join('pembimbing as b','a.pembimbing_id','=','b.id')
                    ->select('b.id','b.nama')
                    ->whereMonth('a.created_at', $month)
                    ->whereYear('a.created_at', $year)
                    ->where('b.nama','LIKE',"%$search%")
                    ->groupBy('b.id','b.nama')
                    ->get();

            }

            return response()->json($data);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }
}
