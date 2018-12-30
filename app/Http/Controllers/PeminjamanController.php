<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Pinjaman;
use App\Model\Barang;
use Illuminate\Support\Facades\DB;
class PeminjamanController extends Controller
{
    //
    public function create(Request $request){
        $data = new Pinjaman;
        $data->nim_peminjam = $request->json('nim_peminjam');
        $data->nama_peminjam = $request->json('nama_peminjam');
        $data->asal_organisasi = $request->json('asal_organisasi');
        $data->tanggal_peminjaman = $request->json('tanggal_peminjaman');
        $data->status = "meminjam";
        $data->url_surat = $request->json('url_surat');
        $data->url_ktm = $request->json('url_ktm');
        $data->status_pengajuan = "sedang diproses";
        $data_barang = $request->json('data_barang');
        if ($data_barang === NULL){
            return response()->json("data barang yang dinput tidak ditemukan",404);
        }else{
            $data->save();
            foreach ($data_barang as $tmp) {
                DB::table('pinjaman_detail')->insert([
                    'id_pinjaman' => $data->id_pinjaman, 
                    'id_barang' => $tmp['id_barang'],
                    'jumlah_pinjam'=> $tmp['jumlah_pinjam'],
                    'status'=>'belum di approve'
                ]);            
            }
        }
        return response()->json("data peminjaman berhasil disimpan",200);
    }
    public function pengajuan(){
        $data = Pinjaman::where('status_pengajuan','sedang diproses')
                ->get();
        return $data;
    }
    public function approved($id_pinjaman){
        $pinjam = Pinjaman::find($id_pinjaman);
        if ($pinjam === NULL){
            return response()->json("data yang anda cari tidak ditemukan",404);
        }else {
            $pinjam->status = "dipinjam";
            $pinjam->status_pengajuan = "approved";
            $pinjam_detail = DB::table('pinjaman_detail')
                            ->where('id_pinjaman', $id_pinjaman)
                            ->get();
            if($pinjam_detail === NULL){
                return response()->json("data barang yang dipinjam tidak ditemukan",404);
            }else{
                $pinjam->save();
                foreach ($pinjam_detail as $tmp) {
                    $curent = Barang::find($tmp->id_barang);
                    if ($curent->stok < $tmp->jumlah_pinjam){
                        $update_detail = DB::table('pinjaman_detail')
                                ->where('id_pinjaman', $id_pinjaman)
                                ->update(['status'=> 'tidak dipinjamkan']); 
                    }else{
                        $update_detail = DB::table('pinjaman_detail')
                                ->where('id_pinjaman', $id_pinjaman)
                                ->update(['status'=> 'dipinjamkan']);
                        $curent->stok = $curent->stok - $tmp->jumlah_pinjam;
                        $curent->save();
                    }
                }
            }
        }
        return response()->json("berhasil menyetujui pengajuan peminjaman barang",200);
    }
    public function listPeminjamBarang(){
        $data = Pinjaman::where('status_pengajuan','approved')
                ->get();
        return $data;
    }
    public function pengembalianBarang($id_pinjaman){
        $pinjam_detail = DB::table('pinjaman_detail')
                ->where('id_pinjaman', $id_pinjaman)
                ->get();
        foreach ($pinjam_detail as $tmp) {
            $curent = Barang::find($tmp->id_barang);
            $curent->stok = $curent->stok + $tmp->jumlah_pinjam;
            $curent->save();
        } 
        $update_detail = DB::table('pinjaman_detail')
                        ->where('id_pinjaman', $id_pinjaman)
                        ->update(['status'=> 'sudah dikembalikan']);
        $data = Pinjaman::find($id_pinjaman);
        $data->status = "sudah dikembalikan";
        $data->save();
        return response()->json("berhasil melakukan pengembalian barang",200);        
    }
    public function statusPeminjamanUser($nim_peminjam){
        $data = Pinjaman::where('nim_peminjam',$nim_peminjam)
                ->first();
        if( $data === NULL ){
            return response()->json("data pengajuan peminjaman tidak ditemukan, silahkan menginputkan nim yang benar",404);
        }else{
            return $data->status_pengajuan;
        }
    }
    public function dataArray(Request $request){
        $data = $request->json('data');
        echo count($data);
        foreach ($data as $tmp){
            echo $tmp['id_barang']."<br>";
        }
    }

}
