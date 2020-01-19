<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use Carbon\Carbon;
use App\Product;
use App\Transaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Auth::check()) {
            if(Auth::user()->role != 'ROLPB') {
                $this->middleware('auth');
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            if(Auth::user()->role == 'ROLPJ') {

                $transaksi = Transaction::whereHas('detail.product.store', function($query){
                  $query->where('username', Auth::user()->username);
                })
                ->whereHas('history', function($query){
                  $query->where('status', 'accepted');
                })
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('created_at', '<=', Carbon::now()->endOfMonth())
                ->get();

                return view('seller.home', ['transaksi' => $transaksi]);
            } else if(Auth::user()->role == 'ROLAD' || Auth::user()->role == 'ROLSA') {

                $transaksi = Transaction::whereHas('history', function($query){
                  $query->where('status', 'accepted');
                });

                for ($i=1; $i <= 12; $i++) {
                  $data[$i] = Transaction::whereHas('history', function($query){
                    $query->where('status', 'accepted');
                  })->whereMonth('created_at', $i)->count();
                }

                return view('admin.home', ['transaksi' => $transaksi, 'data' => $data]);
            } else {
                $data['products'] = Product::with(['images' => function ($query) {
                    $query->where('main_image', '=', 'OPTYS');
                }])->limit(6)->get();

                return view('buyer.home', $data);
            }
        } else {
            $data['products'] = Product::with(['images' => function ($query) {
                $query->where('main_image', '=', 'OPTYS');
            }])->limit(6)->get();

            return view('buyer.home', $data);
        }
    }
}
