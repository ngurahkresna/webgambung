<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $data = User::find(Auth::user()->id);
        $city = new OngkirController();

        if ($data->role == 'ROLPB') {
            return view('buyer.profile', ['data' => $data, 'cities' => $city->getAllCities()]);
        } else {
            return view('profile', ['data' => $data, 'cities' => $city->getAllCities()]);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'alamat' => 'required',
            'telfon' => 'required|numeric|digits_between:2,16',
            'city' => 'required',
        ]);

        User::where('id', Auth::user()->id)
            ->update([
                'name' => $request->name,
                'city' => $request->city,
                'birthday' => $request->birthday,
                'address_1' => $request->alamat,
                'phone' => $request->telfon,
            ]);

        Alert::success('Berhasil', 'Berhasil mengubah data profile');
        return redirect('/profile');

    }

    public function password(Request $request)
    {
        $request->validate([
            'passwordlama' => 'required|string',
            'password' => 'required|min:8|string|confirmed',
        ]);

        $user = User::where('id', Auth::user()->id)->get();
        if (Hash::check($request->passwordlama, $user[0]->password)) {
            User::where('id', Auth::user()->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
            Alert::success('Berhasil', 'Berhasil mengubah password');
            return redirect('/profile');
        } else {
            Alert::error('Gagal', 'Gagal mengubah password');
            return redirect('/profile');
        }

    }

    public function avatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file'
        ]);

        $file = $request->file('avatar');

        $fileName = rand() . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/img/avatar/', $fileName);

        User::where('id', Auth::user()->id)->update(['avatar' => '/' . $fileName]);
        Alert::success('Berhasil', 'Berhasil mengubah foto profile');
        return redirect('/profile');
    }

}
