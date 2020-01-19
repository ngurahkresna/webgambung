<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\User;
use App\Notification;
use App\Transaction;
use App\TransactionPayment;
use Carbon\Carbon;

class PembayaranController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index($code){

    $data['transactions'] = Transaction::with('detail','payment','detail.product')
    ->where('username', Auth::user()->username)
    ->where('code', $code)
    ->whereHas('payment', function($query){
      $query->where('updated_process','pembayaran');
    })
    ->get();

    return view('buyer.bayar', $data);
  }

  public function upload_pembayaran(Request $request){
    $request->validate([
      'foto' => 'required',
    ]);

    $file = $request->file('foto');
    $nama_file = rand().$file->getClientOriginalName();
    $file->move(public_path().'/assets/img/proof',$nama_file);

    TransactionPayment::where('transaction_code', $request->transaction_code)
    ->update([
      'deadline_proof' => Carbon::now()->add(1,'day'),
      'status_upload' => 'OPTYS',
      'proof_image' => $nama_file,
      'proof_date' => Carbon::now(),
      'updated_by' => Carbon::now(),
      'updated_process' => 'verifikasi',
    ]);

    Notification::insert([
      'id_users' => Auth::user()->id,
      'notification_message' => "Transaksi ".$request->code." sudah masuk tahap verifikasi.",
      'info' => 'notification',
      'notification_read' => 'OPTNO',
      'created_at' => Carbon::now(),
    ]);

    $admins = User::where('role', 'ROLAD')
    ->orWhere('role', 'ROLSA')
    ->get();

    foreach ($admins as $admin) {
      Notification::insert([
        'id_users' => $admin->id,
        'notification_message' => "Transaksi ".$request->code." sudah melakukan pembayaran.",
        'info' => 'notification',
        'notification_read' => 'OPTNO',
        'created_at' => Carbon::now(),
      ]);
    }

    Alert::success('Berhasil', 'Bukti pembayaran sudah dikirim, silahkan menunggu konfirmasi');
    return redirect('/verifikasi/'.$request->transaction_code);
  }

}
