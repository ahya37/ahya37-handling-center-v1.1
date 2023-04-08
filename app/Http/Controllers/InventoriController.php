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
            if(empty($request_stok)) return 'kosong';
    
            $stok['stok'] = $request_stok;
    
    
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


            DB::commit();
            return redirect()->route('opname')->with(['success' => 'Stok opname telah disimpan!']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('stockout')->with(['error' => 'Gagal disimpan!']);
        }

    }
}
