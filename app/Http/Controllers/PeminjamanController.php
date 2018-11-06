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
        $data->status = "dipinjam";
        $data->url_surat = $request->json('url_surat');
        $data->url_ktm = $request->json('url_ktm');
        $data->status_pengajuan = "sedang diproses";
        $data->save();
        dd($data);
        // $data_barang = $request->json('data_barang');
        // foreach ($data_barang as $tmp) {
        //     DB::table('pinjaman_detail')->insert([
        //         'id_pinjaman' => $data->id_pinjaman, 
        //         'id_barang' => $tmp['id_barang'],
        //         'jumlah_pinjam'=> $tmp['jumlah_pinjam']
        //     ]);            
        // }
        return response("data peminjaman berhasil disimpan",200);
    }
    public function pengajuan(){
        $data = Pinjaman::where('status_pengajuan','sedang diproses')
                ->get();
        return $data;
    }
    public function approved($id_pinjaman){
        $pinjam = Pinjaman::find($id_pinjaman);
        $pinjam->status_pengajuan = "approved";
        $pinjam->save();

        $pinjam_detail = DB::table('pinjaman_detail')
                ->where('id_pinjaman', $id_pinjaman)
                ->get();
        dd($pinjam_detail);
        foreach ($pinjam_detail as $tmp) {
            $curent = Barang::find($tmp->id_barang);
            $curent->stok = $curent->stok - $tmp->jumlah_pinjam;
            $curent->save();
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
