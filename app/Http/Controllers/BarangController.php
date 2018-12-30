<?php

namespace App\Http\Controllers;
use App\Model\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    //
    // public function __construct(){
    //     $this->middleware('auth');
    // }
    public function index(Request $request){
        $data = Barang::all();
        return $data;
    }
    public function create(Request $request){
        $data = new Barang;
        $data->nama_barang = $request->json('nama_barang');
        $data->stok = $request->json('stok');
        $data->address_picture = $request->json('address_picture');
        $data->save();
        // dd($data);
        return response()->json('data barang berhasil disimpan');
    }
    public function edit($id_barang){
        $data = Barang::find($id_barang);
        if($data === NULL){
            return response()->json('data barang tidak ditemukan');
        }else{
            return $data;
        }
    }
    public function update(Request $request){
        $data = Barang::find($request->$id_barang);
        if($data === NULL){
            return response()->json('data barang yang ingin di update tidak ada');
        }else{
            $data->nama_barang = $request->json('nama_barang');
            $data->stok = $request->json('stok');
            $data->address_picture = $request->json('address_picture');
            $data->save();
            return response()->json('data barang berhasil di update',200);
        }
         
    }
    public function delete($id_barang){
        $data = Barang::find($id_barang);
        if($data === NULL){
            return response()->json('data barang yang ingin dihapus tidak ada');
        }else{
            $data->delete();
            return response()->json('data barang berhasil di hapus',200);
        }  
    }

}
