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
    public function stokMasuk(Request $request){

        $items = DB::table('rb_item as a')
                ->select('a.it_idx','a.it_name','a.it_desc','a.it_image','a.it_update','b.ic_count')
                ->join('rb_item_count as b','a.it_idx','=','b.ic_itidx')
                ->get();

        return view('inventori.stokmasuk.create',compact('items'));
    }

    public function storeStokMasuk(Request $request){

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(),[
                'iditem' => 'required',
                'stok' => 'required',
            ]);

            if ($validator->fails()) return redirect()->route('item.create')->with(['error' => 'Pilih item & stok tidak boleh kosong!']);

            #jika stok masuk <= 0 , maka peringati
            if ($request->stok <= 0) return redirect()->back()->with(['error' => 'Stok tidak boleh 0 / minus']); 

            #get stok sebelumnya by iditem di tb rb_item_count
            $old_stok =  ItemCountModel::select('ic_count')->where('ic_itidx', $request->iditem)->first();

            #simpan ke tb rb_item_inventory
                #in_count = stok masuk
                #in_count_first = stok sebelumnya, ic_count by rb_item_count
                #in_count_last  = in_count_first + stok masuk
            $ItemInventori = ItemInventoriModel::create([
                    'in_id' => Str::random(30),
                    'in_itidx' => $request->iditem,
                    'in_count' => $request->stok,
                    'in_count_first' => $old_stok->ic_count,
                    'in_count_last' => $old_stok->ic_count + $request->stok,
                    'in_desc'       => $request->note,
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
            
            DB::commit();
            return redirect()->route('stokmasuk')->with(['success' => 'Stok telah disimpan!']);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            return redirect()->route('item.index')->with(['error' => 'Gagal disimpan!']);
        }


    }
}
