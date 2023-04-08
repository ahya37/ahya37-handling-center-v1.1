<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemModel;
use App\ItemInventoriModel;
use App\ItemCountModel;
use Illuminate\Support\Facades\Validator;
use Auth;
use Str;
use DB;

class ItemController extends Controller
{
    public function index(){

        return view('inventori.item.index');

    }

    public function create(){

        return view('inventori.item.create');

    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
          
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'price' => 'required',
                'qty' => 'required',
                'image' => 'image'
            ]);
    
            if ($validator->fails()) {
    
                return redirect()->route('item.create')->with(['warning' => 'Uplad gambar dengan format image saja!']);
            }
    
            if ($request->hasFile('image')) {
                $fileImage = $request->image->store('images/inventori', 'public');
            }else{
                $fileImage = NULL;
            }
    
            $item = ItemModel::create([
                'it_idx'  => Str::random(30),
                'it_name' => $request->name,
                'it_barcode' => strtoupper(Str::random(10)),
                'it_desc' => $request->note,
                'it_price' => $request->price,
                'it_image' => $fileImage,
                'it_useridx' => Auth::user()->id,
                'it_create' => date('Y-m-d H:i:s'),
            ]);

            #save stok awal item baru
           $itemInventori = ItemInventoriModel::create([
                'in_id' => Str::random(30),
                'in_itidx' => $item->it_idx,
                'in_count' => $request->qty,
                'in_count_first' => $request->qty,
                'in_count_last' => $request->qty,
                'in_create' => date('Y-m-d H:i:s'),
                'in_useridx' => Auth::user()->id,
            ]);

            #save hitung stok item
            ItemCountModel::create([
                'ic_itidx' => $item->it_idx,
                'ic_count' => $itemInventori->in_count_last,
                'ic_create' => date('Y-m-d H:i:s'),
                'ic_useridx' => Auth::user()->id,
            ]);
            
            DB::commit();
            return redirect()->route('item.index')->with(['success' => 'Item telah disimpan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('item.index')->with(['error' => 'Gagal disimpan!']);
        }

    }

    public function listData(Request $request)
    {
        $orderBy = 'a.it_name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.it_name';
                break;
        }

        $data =  DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_desc','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx');

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {

            $results[] = [
                'no' => $no++,
                'id' => $value->it_idx,
                'name' => $value->it_name,
                'image' => $value->it_image,
                'stok' => $value->ic_count,
                'created_at' => $value->it_update,
            ];
        }  
        
        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);

    }
}
