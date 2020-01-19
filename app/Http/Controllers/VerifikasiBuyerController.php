<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Transaction;
use App\TransactionPayment;
use Carbon\Carbon;

class VerifikasiBuyerController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(){

    $data['transactions'] = Transaction::with('detail','payment','detail.product')
    ->where('username', Auth::user()->username)
    ->whereHas('payment', function($query){
      $query->where('updated_process','verifikasi');
    })
    ->get();

    if ($data['transactions'] == "[]") {
      return redirect('/home');
    }

    return view('buyer.verifikasi', $data);
  }
}
