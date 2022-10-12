<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PanduanModel;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Helpers\ResponseFormatter;
use Yajra\DataTables\Facades\DataTables;

class PanduanController extends Controller
{
    public function index()
    {
        return view('panduan.index');
    }

    public function create()
    {
        return view('panduan.create');
    }

    public function store(Request $request)
    {
       
        DB::beginTransaction();
        try {

            $request->validate([
                'judul' => 'required|string',
            ]);

            PanduanModel::create([
                'judul' => $request->judul,
                'slug'  => \Str::slug($request->judul),
                'desc'  => $request->desc
            ]);
    
            DB::commit();

            return redirect()->route('panduan.index')->with(['success' => 'Panduan telah disimpan']);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }

    }

    public function listData()
    {
        $panduan = PanduanModel::orderBy('created_at','desc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($panduan)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
                                <a href="'.route('panduan.edit', $item->id).'" class="btn btn-sm text-primary fa fa-edit" title="Detail"></a>
                                <button  class="btn btn-sm text-danger fa fa-trash" onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->group_date.'" title="Hapus"></button>
                                '
                                ;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function edit($id)
    {
        $panduan = PanduanModel::where('id', $id)->first();
        return view('panduan.edit', compact('panduan'));
    }

    public function update(Request $request, $id)
    {
       
        DB::beginTransaction();
        try {

            $request->validate([
                'judul' => 'required|string',
            ]);

            $panduan = PanduanModel::where('id', $id)->first();
            $panduan->update([
                'judul' => $request->judul,
                'slug'  => \Str::slug($request->judul),
                'desc'  => $request->desc
            ]);
    
            DB::commit();

            return redirect()->route('panduan.index')->with(['success' => 'Panduan telah diubah']);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }

    }

    public function panduanPembimbing()
    {
        return view('users.panduan.index');
    }

    public function searchPanduan()
    {
        $data = request()->data;
        $result = PanduanModel::select('judul','slug')->get();
        if ($data != '') {
            $result = PanduanModel::select('judul','slug')->where('judul','like','%' . $data . '%')->get();
        }
        
        return  response()->json($result);
    }

    public function show($slug)
    {
        $panduan = PanduanModel::where('slug', $slug)->first();
        return view('users.panduan.show', compact('panduan'));
    }
	
	public function deletePanduan()
    {
        DB::beginTransaction();
        try {

            $id      = request()->id;
            $panduan = PanduanModel::where('id', $id)->first();
            $panduan->delete();

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus panduan'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }
}
