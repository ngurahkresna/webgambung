<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\User;
use Alert;

class ManajemenAdminController extends Controller
{

    private $ongkir;

    public function __construct()
    {
        $this->middleware('auth');
        $this->ongkir = new OngkirController();
    }

    public function index()
    {
        $admin['admin'] = DB::table('users')
            ->where('role', 'ROLAD')
            ->get();

        $admin['cities'] = $this->ongkir->getAllCities();

        return view('superadmin.admin', $admin);
    }

    public function delete(Request $request)
    {

        $id = $request->id;

        DB::table('users')->where('id', $id)->delete();
        Alert::success('Berhasil', 'Berhasil menghapus data penjual');

        return redirect('/manajemen-admin');

    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|max:255',
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

        $nama_admin = $request->nama_admin;
        $username = $request->username;
        $kota = $request->kota;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $nomor_telephone = $request->nomor_telephone;
        $email = $request->email;
        $password = $request->password;

        DB::table('users')->insert([
            'name' => $nama_admin,
            'username' => $username,
            'city' => $kota,
            'birthday' => $tanggal_lahir,
            'address_1' => $alamat,
            'phone' => $nomor_telephone,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'ROLAD',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Alert::success('Berhasil', 'Berhasil menambahkan data admin');
        return redirect('/manajemen-admin');

    }

    public function get(Request $request)
    {
        $id = $request->id;
        $admin = DB::table('users')
            ->where('role', 'ROLAD')
            ->where('id', $id)
            ->get();

        return $admin;
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|max:255',
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

        $nama_admin = $request->nama_admin;
        $kota = $request->kota;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $nomor_telephone = $request->nomor_telephone;
        $password = $request->password;
        $repassword = $request->password_confirmation;

        if (($password != null) && ($repassword != null) && ($password == $repassword)) {
            DB::table('users')
                ->where('id', $request->button)
                ->update([
                    'name' => $nama_admin,
                    'city' => $kota,
                    'birthday' => $tanggal_lahir,
                    'address_1' => $alamat,
                    'phone' => $nomor_telephone,
                    'password' => bcrypt($password),
                    'updated_at' => Carbon::now(),
                ]);
        } else {
            DB::table('users')
                ->where('id', $request->button)
                ->update([
                    'name' => $nama_admin,
                    'city' => $kota,
                    'birthday' => $tanggal_lahir,
                    'address_1' => $alamat,
                    'phone' => $nomor_telephone,
                    'updated_at' => Carbon::now(),
                ]);
        }

        Alert::success('Berhasil', 'Berhasil mengubah data admin');
        return redirect('/manajemen-admin');

    }

    public function search(Request $request)
    {
        $value = $request->value;
        $data = User::where([
            ['role', '=', 'ROLAD'],
            ['username', 'LIKE', "%{$value}%"],
            ['email', 'LIKE', "%{$value}%"],
        ])->get();

        return response()->json($data);
    }

}
