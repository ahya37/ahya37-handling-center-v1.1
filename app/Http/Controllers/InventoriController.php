<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemModel;
use App\ItemInventoriModel;
use App\ItemCountModel;
use App\ItemBundleModel;
use App\ItemBundleDetailModel;
use Illuminate\Support\Facades\Validator;
use Auth;
use Str;
use DB;
use PDF;

class InventoriController extends Controller
{
    public function stockIn(Request $request){

        $items = DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_desc','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx')
                ->where('a.is_delete',0)
                ->get();

        return view('inventori.stockin.create',compact('items'));
    }

    public function storeStockIn(Request $request){

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'stok' => 'required',
            ]);

            if ($validator->fails()) return redirect()->route('item.create')->with(['error' => 'Pilih item & stok tidak boleh kosong!']);

            #jika stok masuk <= 0 , maka peringati
            if ($request->stok <= 0) return redirect()->back()->with(['error' => 'Stok masuk tidak boleh 0 / minus']); 

            #get stok sebelumnya by iditem di tb rb_item_count
            $old_stok =  ItemCountModel::select('ic_count')->where('ic_itidx', $request->iditem)->first();

            #simpan ke tb rb_item_inventory
            #simpan ke tb rb_item_inventory & rb_item_count
            $this->updateStock($request,$old_stok,'in');
            
            DB::commit();
            return redirect()->route('stockin')->with(['success' => 'Stok masuk telah disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            // return $e->getMessage();
            return redirect()->route('stockin')->with(['error' => 'Gagal disimpan!']);
        }


    }

    public function stockout(Request $request){

        $items = DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_desc','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx')
                ->where('a.is_delete',0)
                ->get();

        return view('inventori.stockout.create',compact('items'));
    }

    public function storeStockout(Request $request){

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'stok' => 'required',
            ]);

            if ($validator->fails()) return redirect()->route('item.create')->with(['error' => 'Pilih item & stok tidak boleh kosong!']);

            
            #get stok sebelumnya by iditem di tb rb_item_count
            $old_stok =  ItemCountModel::select('ic_count')->where('ic_itidx', $request->iditem)->first();
            
            #jika stok masuk <= 0 , maka peringati
            if ($request->stok <= 0) return redirect()->back()->with(['error' => 'Stok keluar tidak boleh 0 / minus']); 
            
            #stok keluar tidak boleh melebih stok tersedia
            if($request->stok > $old_stok->ic_count) return redirect()->back()->with(['error' => 'Stok keluar melebihi stok yang tersedia']); 

            #simpan ke tb rb_item_inventory & rb_item_count
            $this->updateStock($request,$old_stok,'out');
            
            DB::commit();
            return redirect()->route('stockout')->with(['success' => 'Stok telah keluar!']);

        } catch (\Exception $e) {
            DB::rollback();
            // return $e->getMessage();
            return redirect()->route('stockout')->with(['error' => 'Gagal disimpan!']);
        }


    }

    public function updateStock($request,$old_stok, $status){

        #jika status out maka kuangi, jika in maka tamnbah
        $in_count_last = $status == 'out' ? $old_stok->ic_count - $request->stok : $old_stok->ic_count + $request->stok;


        #in_count = stok masuk
                #in_count_first = stok sebelumnya, ic_count by rb_item_count
                #in_count_last  = in_count_first + stok masuk

        #in_count = stok keluar
                #in_count_first = stok sebelumnya, ic_count by rb_item_count
                #in_count_last  = in_count_first - stok masuk
        $ItemInventori = ItemInventoriModel::create([
                    'in_id' => Str::random(30),
                    'in_itidx' => $request->iditem,
                    'in_count' => $request->stok,
                    'in_count_first' => $old_stok->ic_count,
                    'in_count_last' => $in_count_last,
                    'in_desc'       => $request->note,
                    'in_status'     => $status,
                    'in_create' => date('Y-m-d H:i:s'),
                    'in_useridx' => Auth::user()->id,
        ]);

        # update stok di rb_item_count by iditem
                # ic_count = in_count_last
        DB::table('rb_item_count')->where('ic_itidx', $request->iditem)->update([
                    'ic_count' => $ItemInventori->in_count_last,
                    'ic_update' => date('Y-m-d H:i:s'),
                    'ic_useridx' => Auth::user()->id
        ]);

    }

    public function opname(){

        return view('inventori.opname.create');

    }

    public function storeOpname(Request $request){

        DB::beginTransaction();
        try {

            $iditem       = $request->iditem;
            $stok['stok'] = $request->stok;
    
            #cek jika input sto kosong
            $request_stok = array_filter($request->stok);
            if(empty($request_stok)) return redirect()->route('opname')->with(['error' => 'Input stok tidak boleh kosong!']);
            // $stok['stok'] = $request_stok;

    
            foreach ($iditem as $key => $value) {

                #update yg ada stok nya saja
                if (isset($stok['stok'][$key])) {
                    #get stok sebelumnya by iditem di tb rb_item_count
                    $old_stok =  ItemCountModel::select('ic_count')->where('ic_itidx', $value)->first();
        
                    #save ke rb_item_inventori
                    $ItemInventori = ItemInventoriModel::create([
                            'in_id' => Str::random(30),
                            'in_itidx' => $value,
                            'in_count' => $stok['stok'][$key],
                            'in_count_first' => $old_stok->ic_count,
                            'in_count_last' => $stok['stok'][$key],
                            'in_status'     => 'opname',
                            'in_create' => date('Y-m-d H:i:s'),
                            'in_useridx' => Auth::user()->id,
                    ]);
            
                    # update stok di rb_item_count by iditem
                    # ic_count = in_count_last
                    DB::table('rb_item_count')->where('ic_itidx', $value)->update([
                            'ic_count' => $ItemInventori->in_count_last,
                            'ic_update' => date('Y-m-d H:i:s'),
                            'ic_useridx' => Auth::user()->id
                        ]);
                }
    
                
            }

            #save ke tb rb_item_opname sebagai berita acara
            DB::table('rb_item_opname')->insert([
                'title' => 'Berita acara',
                'created_by' => Auth::user()->id,
            ]);

            DB::commit();
            return redirect()->route('opname')->with(['success' => 'Stok opname telah disimpan!']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('opname')->with(['error' => 'Gagal disimpan!']);
        }

    }

    public function history(){

        $items = ItemModel::select('it_name','it_idx')->orderBy('it_name','asc')->get();

        return view('inventori.history.index', compact('items'));

    }

    public function getDataHistory(Request $request){
        
        $orderBy = 'b.it_name';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'b.it_name';
                break;
        }

        $data =  DB::table('rb_item_inventory as a')
                 ->select('a.in_idx','b.it_name','b.it_image','a.in_count','a.in_count_first','a.in_count_last','a.in_status','a.in_create')
                 ->join('rb_item as b','a.in_itidx','=','b.it_idx')
                 ->where('b.is_delete',0);

        if($request->input('search.value')!=null){
                    $data = $data->where(function($q)use($request){
                        $q->whereRaw('LOWER(b.it_name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                    });
        }

        if($request->input('item') != ''){
            $data->where('a.in_itidx', $request->item);
        }

        if($request->input('status') != ''){
            $data->where('a.in_status', $request->status);
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
                'id' => $value->in_idx,
                'name' => $value->it_name,
                'qty' => $value->in_count,
                'first' => $value->in_count_first,
                'last' => $value->in_count_last,
                'status' => $value->in_status,
                'created_at' => date('d-m-Y', strtotime($value->in_create)),
            ];
        }  
        
        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $results
            ]);

    }

    public function stockOutBundle(){

        $bundles = DB::table('rb_item_bundle as a')
                    ->join('rb_item_bundle_detail as b','a.ib_idx','=','b.ibd_ibidx')
                    ->select('a.ib_name','a.ib_note','a.ib_create','a.ib_idx', DB::raw('count(if(b.is_delete = 0, 1, NULL)) as count_item'))
                    ->groupBy('a.ib_name','a.ib_note','a.ib_create','a.ib_idx')
                    ->where('a.is_delete',0)->get();

        return view('inventori.bundle.stockout',compact('bundles'));
    }

    public function storeStockoutBundle(Request $request){

        DB::beginTransaction();
        try {

            $idBundle   = $request->idbundle;
            $qty        = $request->qty ?? 1; // jika kosong default nya 1

            #get iditem yang ada di detail item bundle
            $itemBundleDetail = ItemBundleDetailModel::select('ibd_itidx','ibd_count')->where('ibd_ibidx', $idBundle)->where('is_delete',0)->get();
            
            #looping, dan simpan ke tb_inventory dengan status out
            foreach ($itemBundleDetail as $value) {
                #get count terkakhir by item 
                $old_stok =  ItemCountModel::select('ic_count')->where('ic_itidx', $value->ibd_itidx)->first();
                #save ke tb_item_inventory
                $total_qty = $qty * $value->ibd_count;
                $ItemInventori = ItemInventoriModel::create([
                    'in_id' => Str::random(30),
                    'in_itidx' => $value->ibd_itidx,
                    'in_count' => $total_qty,
                    'in_count_first' => $old_stok->ic_count,
                    'in_count_last' => $old_stok->ic_count - $total_qty,
                    'in_status'     => 'out',
                    'in_bundle'     => 'Y',
                    'in_create' => date('Y-m-d H:i:s'),
                    'in_useridx' => Auth::user()->id,
                ]);

                #update count / stok
                DB::table('rb_item_count')->where('ic_itidx', $value->ibd_itidx)->update([
                    'ic_count' => $ItemInventori->in_count_last,
                    'ic_update' => date('Y-m-d H:i:s'),
                    'ic_useridx' => Auth::user()->id
                ]);

            }

            DB::commit();
            return redirect()->route('bundle-stockout')->with(['success' => 'Stok out bundel telah disimpan!']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('bundle-stockout')->with(['error' => 'Gagal disimpan!']);
        }
    }

    public function report(){

        return view('inventori.report.index');
    }

    public function storeReport(Request $request){

        // $pdf = PDF::LoadView('inventori.report.opname');
        // return $pdf->stream('Berita Acara Stok Opname Persediaan Perlengkapan.pdf');

        if($request->type == 'opname'){

            $date = date('Y-m-d', strtotime(request('date')));

            $items = DB::table('rb_item_inventory as a')
                    ->select('b.it_name','c.ic_count')
                    ->join('rb_item as b','a.in_itidx','=','b.it_idx')
                    ->join('rb_item_count as c','c.ic_itidx','b.it_idx')
                    ->where('a.in_status', 'opname')
                    ->whereDate('a.in_create', $date)
                    ->groupBy('b.it_name','c.ic_count')
                    ->get();


            if (count($items) > 0) {
    
                $total = collect($items)->sum(function($q){
                    return $q->ic_count;
                });
                
                $no = 1;
                #PDF
                $pdf = PDF::LoadView('inventori.report.opname', compact('items','no','total'));
                return $pdf->stream('Berita Acara Stok Opname Persediaan Perlengkapan.pdf');
            }else{

                return redirect()->back()->with(['error' => 'Tidak ada opname ditanggal tersebut!']);

            }

            

        }else{

            return redirect()->back()->with(['error' => 'Tidak ada laporan!']);
        }
    }

}
