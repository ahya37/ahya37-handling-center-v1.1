<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TagOrangeModel;
use App\DetailTagOrangeModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ResponseFormatter;
use App\Imports\TagOrangeImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use PDF;
use File;

class OperasionalController extends Controller
{
    public function indexTagOrange()
    {
        return view('tagorange.index');
    }

    public function createGroupTageOrange()
    {
        return view('tagorange.create');
    }

    public function storeTagOrange(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'group_date' => 'string'
             ]);

            TagOrangeModel::create([
                'group_date' => $request->group_date,
                'create_by'  => Auth::user()->id
            ]);

            DB::commit();
                        
            return redirect()->route('tagorange.index')->with(['success' => 'Group telah dibuat']);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listDataTagOrange()
    {
        $tag_orange = TagOrangeModel::orderBy('created_at','desc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($tag_orange)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
                                <a href="'.route('tagorange.addjamaah', $item->id).'" class="btn btn-sm text-primary lni lni-circle-plus" title="Tambah Jamaah"></a>
                                <a href="'.route('tagorange.detail', $item->id).'" class="btn btn-sm text-primary fa fa-eye" title="Detail"></a>
                                <button  class="btn btn-sm text-danger fa fa-trash" onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->group_date.'" title="Hapus"></button>
                                '
                                ;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function listDataDetailTagOrange($id)
    {
        $tag_orange = DetailTagOrangeModel::where('tag_orange_id', $id)->orderBy('created_at','desc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($tag_orange)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
								<button onclick="openModal(this)" class="btn btn-sm btn-primary" id="'.$item->id.'">Upload Foto</button>
                                <a href="'.route('tagorange.jamaah.edit', ['id' => $item->id, 'tagorangeid' => $item->tag_orange_id]).'" class="btn btn-sm text-primary fa fa-edit" title="Edit"></a>
                                <button  class="btn btn-sm text-danger fa fa-trash" onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nama_jamaah.'" title="Hapus"></button>
                                '
                                ;
                    })
                    ->addColumn('foto', function($item){
                        return '
                                 <img src="'.asset('/storage/'.$item->foto_jamaah).'" width="50">
                        ';
                    })
                    ->rawColumns(['action','foto'])
                    ->make(true);
        }
    }

    public function detailGroup($id)
    {
        $id         = $id;
        $tag_orange = TagOrangeModel::where('id', $id)->first();
        return view('tagorange.detail', compact('id','tag_orange'));
    }

    public function addJamaah($id)
    {
        $id         = $id;
        $tag_orange = TagOrangeModel::where('id', $id)->first();
        return view('tagorange.addjamaah', compact('id','tag_orange'));
    }

    public function storeDetailTagOrange(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'no_urut' => 'required',
                'foto' => 'image'
             ]);

            if ($validator->fails()) {

                return redirect()->route('jamaah.create')->with(['success' => 'Uplad foto dengan format image saja !']);
            }

            if ($request->hasFile('foto')) {
                $fileImage = $request->foto->store('images/tagorange/jamaah', 'public');
            }else{
                $fileImage = NULL;
            }

            DetailTagOrangeModel::create([
                'tag_orange_id' => $id,
                'no_urut' => $request->no_urut,
                'nama_jamaah' => strtoupper($request->name),
                'foto_jamaah' => $fileImage,
                'telp_jamaah'  => $request->telp,
                'email_jamaah'  => $request->email,
                'alamat_jamaah'  => $request->address,
            ]);

            DB::commit();
                        
            return redirect()->back()->with(['success' => 'Jamaah telah dibuat']);
            
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function exportTagOrange($id)
    {
        $start = request()->start;
        $end  = request()->end;
        $label = strtoupper(request()->label);

        // GET DATA DETAIL GROUP BY ID
        $tag  = TagOrangeModel::select('group_date')->where('id', $id)->first();
        // $data = DetailTagOrangeModel::where('tag_orange_id', $id)->whereBetween('no_urut',[$start, $end])->get();
        $sql  = "select * from detail_tag_orange where no_urut between $start and $end and tag_orange_id = $id";
        $data = DB::select($sql);
        // $pdf = PDF::LoadView('report.tagorange',compact('data','tag'));
        // return $pdf->stream($id.'.pdf');

        // get no.telp TL / nomor urut 1
        $tl =  DetailTagOrangeModel::select('telp_jamaah')->where('tag_orange_id', $id)->where('no_urut','01')->first();

        return view('report.tagorange', compact('data','tag','label','tl'));
    }

    public function editJamaahDetailTag($id)
    {
        $jamaah    = DetailTagOrangeModel::where('id', $id)->first();
        return view('tagorange.editjamaah', compact('jamaah'));
    }

    public function editDetailTagOrange(Request $request, $id, $tagorangeid)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'no_urut' => 'required',
                'foto' => 'image'
             ]);

            if ($validator->fails()) {

                return redirect()->route('jamaah.create')->with(['success' => 'Uplad foto dengan format image saja !']);
            }

            $jamaah = DetailTagOrangeModel::where('id', $id)->where('tag_orange_id', $tagorangeid)->first();
            
            if ($request->foto != '') {
                // hapus foto lama
                File::delete(storage_path('app/public/'.$jamaah->foto_jamaah));

                if ($request->hasFile('foto')) {
                    $fileImage = $request->foto->store('images/tagorange/jamaah', 'public');
                }

            }else{
                $fileImage = $jamaah->foto_jamaah;
            }

            $jamaah->update([
                'no_urut' => $request->no_urut,
                'nama_jamaah' => strtoupper($request->name),
                'foto_jamaah' => $fileImage,
                'telp_jamaah'  => $request->telp,
                'email_jamaah'  => $request->email,
                'alamat_jamaah'  => $request->address,
            ]);

            DB::commit();
            return redirect()->route('tagorange.detail', ['id' => $tagorangeid])->with(['success' => 'Jamaah telah diubah']);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteTagOrange()
    {
        DB::beginTransaction();
        try {

            $id         = request()->id;
            $tag_orange =  TagOrangeModel::where('id', $id)->first();

            $jamaah     = DetailTagOrangeModel::where('tag_orange_id', $id)->get();
            foreach ($jamaah as $value) {
                if ($value->foto_jamaah != null) {
                    File::delete(storage_path('app/public/'.$value->foto_jamaah));
                    DetailTagOrangeModel::where('tag_orange_id', $id)->delete();
                }else{
                    DetailTagOrangeModel::where('tag_orange_id', $id)->delete();
                }
            }

            $tag_orange->delete();

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus group'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function deleteJamaahDetailTag()
    {
        DB::beginTransaction();
        try {

            $id     = request()->id;
            $jamaah = DetailTagOrangeModel::where('id', $id)->first();

            if ($jamaah->foto_jamaah != null) {
                File::delete(storage_path('app/public/'.$jamaah->foto_jamaah));
                $jamaah->delete();
            }else{
                $jamaah->delete();
            }
            // delete foto
            // DetailTagOrangeModel::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus jamaah'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function importJamaahTag(Request $request, $id)
    {
    
        DB::beginTransaction();
        try {

            if ($request->file('file')) {
                
                Excel::import(new TagOrangeImport($id), request()->file('file'));
                DB::commit();
                return redirect()->route('tagorange.detail', ['id' => $id])->with(['success' => 'Jamaah telah ditambah']);
            }else{
                return redirect()->route('tagorange.detail', ['id' => $id])->with(['warning' => 'Tidak ada file']);
            }

            

        } catch (\Exception $e) {
            DB::rollback();
           return $e->getMessage();

        }
    }

    public function updateTagOrange(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'group_date' => 'string'
             ]);

           $tag_orange = TagOrangeModel::where('id', $id)->first(); 

           $tag_orange->update([
                'group_date' => $request->group_date,
            ]);

            DB::commit();
                        
            return redirect()->route('tagorange.index')->with(['success' => 'Group telah diubah']);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
	
	public function uploadFotoJamaahByModal(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'foto' => 'image'
             ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['success' => 'Uplad foto dengan format image saja !']);
            }

            $jamaah =  DetailTagOrangeModel::where('id', $request->id)->first();

            if ($request->hasFile('foto')) {
                // delete file sebelumnya
                File::delete(storage_path('app/public/'.$jamaah->foto_jamaah));

                $fileImage = $request->foto->store('images/tagorange/jamaah', 'public');
            }else{
                $fileImage = $jamaah->foto_jamaah;
            }

            $jamaah->update([
                'foto_jamaah' => $fileImage,
            ]);

            DB::commit();

            return ResponseFormatter::success([
                null,
                'message' => 'Berhasil hapus jamaah'
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
