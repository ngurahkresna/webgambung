<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\User;
use Alert;

class ManajemenPenjualController extends Controller
{

    private $ongkir;

    public function __construct()
    {
        $this->middleware('auth');
        $this->ongkir = new OngkirController();
    }

    public function index()
    {
        $penjual['penjual'] = DB::table('users')
            ->where('role', 'ROLPJ')
            ->get();

        $penjual['cities'] = $this->ongkir->getAllCities();

        return view('admin.penjual', $penjual);
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        DB::table('users')->where('id', $id)->delete();
        Alert::success('Berhasil', 'Berhasil menghapus data penjual');

        return redirect('/manajemen-penjual');

    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama_penjual' => 'required|max:255',
            'username' => 'required|unique:users,username',
            'kota' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'nomor_telephone' => 'numeric|digits_between:2,16|required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|max:255',
            'password_confirmation' => 'required|max:255',
            'action' => 'required'
        ]);

        $nama_penjual = $request->nama_penjual;
        $username = $request->username;
        $kota = $request->kota;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $nomor_telephone = $request->nomor_telephone;
        $email = $request->email;
        $password = $request->password;

        DB::table('users')->insert([
            'name' => $nama_penjual,
            'username' => $username,
            'city' => $kota,
            'birthday' => $tanggal_lahir,
            'address_1' => $alamat,
            'phone' => $nomor_telephone,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'ROLPJ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Alert::success('Berhasil', 'Berhasil menambahkan data penjual');
        return redirect('/manajemen-penjual');

    }

    public function get(Request $request)
    {
        $id = $request->id;
        $penjual = DB::table('users')
            ->where('role', 'ROLPJ')
            ->where('id', $id)
            ->get();

        return $penjual;

    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_penjual' => 'required|max:255',
            'username' => 'required',
            'kota' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'nomor_telephone' => 'numeric|digits_between:2,16|required',
            'email' => 'required',
            'password' => 'nullable|confirmed|max:255',
            'password_confirmation' => 'nullable|max:255',
            'action' => 'required'
        ]);

        $nama_penjual = $request->nama_penjual;
        $kota = $request->kota;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $nomor_telephone = $request->nomor_telephone;
        $email = $request->email;
        $password = $request->password;

        if (($request->password != null) && ($request->password_confirmation != null) && ($request->password == $request->password_confirmation)) {
            DB::table('users')
                ->where('id', $request->button)
                ->update([
                    'name' => $nama_penjual,
                    'city' => $kota,
                    'birthday' => $tanggal_lahir,
                    'address_1' => $alamat,
                    'phone' => $nomor_telephone,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'updated_at' => Carbon::now(),
                ]);
        } else {
            DB::table('users')
                ->where('id', $request->button)
                ->update([
                    'name' => $nama_penjual,
                    'city' => $kota,
                    'birthday' => $tanggal_lahir,
                    'address_1' => $alamat,
                    'phone' => $nomor_telephone,
                    'email' => $email,
                    'updated_at' => Carbon::now(),
                ]);
        }

        Alert::success('Berhasil', 'Berhasil mengubah data penjual');
        return redirect('/manajemen-penjual');

    }

    public function search(Request $request)
    {
        $value = $request->value;
        $data = User::where([
            ['role', '=', 'ROLPJ'],
            ['username', 'LIKE', "%{$value}%"],
            ['email', 'LIKE', "%{$value}%"],
        ])->get();

        return response()->json($data);
    }

}
