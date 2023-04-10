<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemModel;
use App\ItemBundleModel;
use App\ItemBundleDetailModel;
use Illuminate\Support\Facades\Validator;
use Auth;
use Str;
use DB;

class BundleController extends Controller
{
    public function bundle(){

        return view('inventori.bundle.index');

    }

    public function createBundle(){

        $items = DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_desc','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx')
                ->where('a.is_delete',0)
                ->get();

        return view('inventori.bundle.create', compact('items'));

    }

    public function storeBundle(Request $request){

        // return $request->all();

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'name' => 'required',
            ]);

            
            $count['qty'] = $request->qty; 
            $iditem       = $request->iditem; 
            
            #cek jika tidak ada item dipilih
            if(!$iditem) return redirect()->route('bundle-create')->with(['error' => 'Pilih setidaknya 1 Item!']);

            #cek jika input stok kosong
            $request_qty = array_filter($request->qty);
            if(empty($request_qty)) return redirect()->route('bundle-create')->with(['error' => 'Qty tidak boleh kosong!']);            

            #save ke tb rb_item_bundle
            $bundle = ItemBundleModel::create([
                'ib_idx' => Str::random(30),
                'ib_name' => $request->name,
                'ib_note' => $request->note,
                'ib_create' => date('Y-m-d H:i:s'),
                'ib_useridx' => Auth::user()->id
            ]);


            #save detail bundle berisi item
            foreach ($iditem as $key => $value) {
                
                #buat bundle jika  qty terisi
                if (isset( $count['qty'][$key])) {
                    $itemBundleDetail = new ItemBundleDetailModel();
                    $itemBundleDetail->ibd_ibidx = $bundle->ib_idx;
                    $itemBundleDetail->ibd_itidx = $value;
                    $itemBundleDetail->ibd_count = $count['qty'][$key];
                    $itemBundleDetail->ibd_create = date('Y-m-d H:i:s');
                    $itemBundleDetail->save();
                }
            }
            
            DB::commit();
            return redirect()->route('bundle')->with(['success' => 'Bundel telah disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('bundle')->with(['error' => 'Gagal disimpan!']);
        }

    }

    public function getListDataBundle(Request $request){

        $orderBy = 'a.ib_name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.ib_name';
                break;
        }

        $data =  DB::table('rb_item_bundle as a')
                ->join('rb_item_bundle_detail as b','a.ib_idx','=','b.ibd_ibidx')
                ->select('a.ib_name','a.ib_note','a.ib_create','a.ib_idx', DB::raw('count(b.ibd_idx) as count_item'))
                ->groupBy('a.ib_name','a.ib_note','a.ib_create','a.ib_idx')
                ->where('a.is_delete',0);

        if($request->input('search.value')!=null){
                    $data = $data->where(function($q)use($request){
                        $q->whereRaw('LOWER(a.ib_name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                    });
        }

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        $results = [];
        $no      = 1;
        foreach ($data as $value) {

            $results[] = [
                'no' => $no++,
                'id' => $value->ib_idx,
                'name' => $value->ib_name,
                'qty' => $value->count_item,
                'note' => $value->ib_note ?? '',
                'created_at' => date('d-m-Y', strtotime($value->ib_create)),
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
