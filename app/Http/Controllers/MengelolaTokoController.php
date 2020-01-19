<?php

namespace App\Http\Controllers;

use App\Expedition;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Store;
use Alert;
use DB;

class MengelolaTokoController extends Controller
{
    private $masterdata;

    public function __construct()
    {
        $this->masterdata = new MasterdataController();
        $this->middleware('auth');
    }

    public function index()
    {
        $toko = Store::with('product')
            ->orderBy('id', 'DESC')
            ->get();

        $penjual = DB::table('users')
            ->where('role', 'ROLPJ')
            ->get();

        $expeditions = Expedition::all();

        return view('admin.toko', ['toko' => $toko, 'penjual' => $penjual, 'expeditions' => $expeditions]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        DB::table('tb_store_expedition')->where('store_code', '=', $id)->delete();
        DB::table('tb_store')->where('code', '=', $id)->delete();

        Alert::success('Berhasil', 'Berhasil menghapus data toko');

        return redirect('/mengelola-toko');
    }

    // TODO: masih hardcode kotanya, apakah kotanya pasti di bandung
    public function tambah(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'nomor_toko' => 'required',
            'deskripsi_toko' => 'required',
            'alamat_toko' => 'required',
            'nomor_telephone' => 'numeric|digits_between:2,16',
            'ekspedisi' => 'required',
            'action' => 'required'
        ]);

        $username = $request->username;
        $nomor_toko = $request->nomor_toko;
        $deskripsi_toko = $request->deskripsi_toko;
        $alamat_toko = $request->alamat_toko;
        $nomor_telephone = $request->nomor_telephone;
        $ekspedisi = $request->ekspedisi;

        $code = $this->masterdata->getStoreSequence();

        DB::table('tb_store')->insert([
            'code' => $code,
            'name' => $nomor_toko,
            'username' => $username,
            'description' => $deskripsi_toko,
            'address_1' => $alamat_toko,
            'phone_1' => $nomor_telephone,
            'city' => 'Bandung',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        for ($i = 0; $i < count($ekspedisi); $i++) {
            DB::table('tb_store_expedition')->insert([
                'expedition_code' => $ekspedisi[$i],
                'store_code' => $code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Alert::success('Berhasil', 'Berhasil menambahkan data penjual');
        return redirect('/mengelola-toko');

    }

    public function get(Request $request)
    {
        $id = $request->id;
        $toko = Store::with('users')
            ->where('id', $id)
            ->first();

        return $toko;
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required',
            'deskripsi_toko' => 'required',
            'alamat_toko' => 'required',
            'nomor_telephone' => 'numeric|digits_between:2,16',
            'action' => 'required'
        ]);

        $nama_toko = $request->nama_toko;
        $deskripsi_toko = $request->deskripsi_toko;
        $alamat_toko = $request->alamat_toko;
        $nomor_telephone = $request->nomor_telephone;

        DB::table('tb_store')
            ->where('id', $request->id)
            ->update([
                'name' => $nama_toko,
                'description' => $deskripsi_toko,
                'address_1' => $alamat_toko,
                'phone_1' => $nomor_telephone,
                'updated_at' => Carbon::now(),
            ]);

        Alert::success('Berhasil', 'Berhasil mengubah data toko');
        return redirect('/mengelola-toko');
    }

    public function search(Request $request)
    {
        $value = $request->value;
        $data = Store::where([
            ['name', 'LIKE', "%{$value}%"]
        ])->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
}
