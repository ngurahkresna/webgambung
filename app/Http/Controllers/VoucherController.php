<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Voucher;
use App\VoucherTerms;
use App\Store;
use Alert;
use DB;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $voucher = Voucher::with('term')
            ->orderBy('id', 'DESC')
            ->get();

        $toko = Store::all();

        return view('admin.voucher', ['voucher' => $voucher, 'toko' => $toko]);
    }

    public function delete(Request $request)
    {
        $code = $request->code;
        DB::table('tb_voucher_terms')->where('voucher_code', $code)->delete();
        DB::table('tb_voucher')->where('code', $code)->delete();

        Alert::success('Berhasil', 'Berhasil menghapus data voucher');
        return redirect('/mengelola-voucher');

    }

    public function tambah(Request $request)
    {
        $request->validate([
            'kode' => 'required|max:6|unique:tb_voucher,code',
            'toko' => 'required',
            'jenis' => 'required',
            'nominal' => 'numeric|max:100|required',
            'maksimal' => 'numeric|required',
            'mulai' => 'required',
            'berakhir' => 'required|after:mulai',
            'syarat' => 'required',
            'action' => 'required',
        ]);

        DB::table('tb_voucher')->insert([
            'code' => strtoupper($request->kode),
            'type' => $request->jenis,
            'percentage' => $request->nominal,
            'max_price' => $request->maksimal,
            'start_date' => $request->mulai,
            'end_date' => $request->berakhir,
            'terms' => $request->syarat,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $voucher_code = DB::table('tb_voucher')->orderBy('id', 'DESC')->first();
        $toko = $request->toko;

        if ($toko[0] == 'all') {
            $toko = Store::all();

            $kode_toko = [];
            $i = 0;
            foreach ($toko as $tk) {
                $kode_toko[$i] = $tk->code;

                $i++;
            }
        } else {
            $kode_toko = $toko;
        }

        if (is_array($toko)) {
            for ($i = 0; $i < count($kode_toko); $i++) {
                DB::table('tb_voucher_terms')->insert([
                    'voucher_code' => $voucher_code->code,
                    'store_code' => $kode_toko[$i],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil menambahkan voucher');
        return redirect('/mengelola-voucher');

    }

    public function get(Request $request)
    {
        $id = $request->id;
        $voucher = Voucher::with('term')
            ->where('id', $id)
            ->first();

        return $voucher;
    }

    public function edit(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'toko' => 'required',
            'jenis' => 'required',
            'nominal' => 'numeric|max:100|required',
            'maksimal' => 'numeric|required',
            'mulai' => 'required',
            'berakhir' => 'required|after:mulai',
            'syarat' => 'required',
            'action' => 'required',
        ]);

        DB::table('tb_voucher')
            ->where('id', $request->id)
            ->update([
                'type' => $request->jenis,
                'percentage' => $request->nominal,
                'max_price' => $request->maksimal,
                'start_date' => $request->mulai,
                'end_date' => $request->berakhir,
                'terms' => $request->syarat,
                'updated_at' => Carbon::now(),
            ]);

        VoucherTerms::where('voucher_code', $request->code)->delete();

        $kode_toko = [];
        $toko = $request->toko;
        if ($toko[0] == 'all') {
            $toko = Store::all();

            $i = 0;
            foreach ($toko as $tk) {
                $kode_toko[$i] = $tk->code;

                $i++;
            }
        } else {
            $kode_toko = $toko;
        }

        if (is_array($request->toko)) {
            for ($i = 0; $i < count($kode_toko); $i++) {
                DB::table('tb_voucher_terms')->insert([
                    'voucher_code' => $request->kode,
                    'store_code' => $kode_toko[$i],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil mengubah data voucher');
        return redirect('/mengelola-voucher');

    }

    public function search(Request $request)
    {
        $value = $request->value;
        $voucher = Voucher::where([
            ['code', 'LIKE', "%{$value}%"],
            ['type', 'LIKE', "%{$value}%"],
            ['percentage', 'LIKE', "%{$value}%"],
            ['start_date', 'LIKE', "%{$value}%"],
            ['end_date', 'LIKE', "%{$value}%"],
        ])->orderBy('id', 'DESC')->get();

        $voucher = Voucher::where('code', 'LIKE', "%{$value}%")
            ->orWhere('type', 'LIKE', "%{$value}%")
            ->orWhere('percentage', 'LIKE', "%{$value}%")
            ->orWhere('start_date', 'LIKE', "%{$value}%")
            ->orWhere('end_date', 'LIKE', "%{$value}%")
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($voucher);
    }

}
