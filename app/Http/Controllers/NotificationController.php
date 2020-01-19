<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Notification;

class NotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function notification()
  {

    $id = Auth::user()->id;

    $data['messages'] = Notification::with('users','users.store')
    ->where('id_users', $id)
    ->orderBy('created_at', 'DESC')
    ->where('info', 'message')
    ->limit(5)
    ->get();
    $message_str = "";
    $data['message_new'] = 'no';
    if ($data['messages'] != "[]") {
      foreach ($data['messages'] as $message) {
        if ($message->notification_read == 'OPTNO') {
          $data['message_new'] = 'new';
        }
        $message_str = $message_str.'
        <a class="dropdown-item text-left py-2 text-dark m-0 notif" href="/chat">
        <span class="font-weight-bold text-success">'.$message->from_user->store->name.'</span><br> mengirim anda pesan
        <p class="m-0 p-0">'.$message->created_at.'</p>
        </a>
        <div class="dropdown-divider"></div>
        ';
      }
    }
    $data['messages'] = $message_str;

    $data['notifications'] = Notification::with('users','users.store')
    ->where('id_users', $id)
    ->orderBy('created_at', 'DESC')
    ->where('info', 'notification')
    ->limit(5)
    ->get();

    $transaction_str = "";
    $data['notif_new'] = 'no';
    if ($data['notifications'] != "[]") {
      foreach ($data['notifications'] as $notif) {
        if ($notif->notification_read == 'OPTNO') {
          $data['notif_new'] = 'new';
        }
        $transaction_str = $transaction_str.'
        <a class="dropdown-item text-left py-2 text-dark m-0 notif" href="/transaksi" >
        <span class="font-weight-bold text-success">'.$notif->notification_message.'
        </span><p class="m-0 p-0">'.$notif->created_at.'</p>
        </a>
        <div class="dropdown-divider"></div>
        ';
      }
    }
    $data['notifications'] = $transaction_str;

    return $data;

  }

  public function fetch(Request $request)
  {

    $id = Auth::user()->id;
    Notification::with('users')
    ->where('id_users', $id)
    ->where('info', $request->data)
    ->update([
      'notification_read' => 'OPTYS',
    ]);

    return ['status' => "berhasil"];
  }

  public function notification_dashboard(){

    $id = Auth::user()->id;

    $data['messages'] = Notification::with('users','users.store')
    ->where('id_users', $id)
    ->orderBy('created_at', 'DESC')
    ->where('info', 'message')
    ->limit(5)
    ->get();
    $message_str = "";
    $data['message_new'] = 'no';
    if ($data['messages'] != "[]") {
      foreach ($data['messages'] as $message) {
        if ($message->notification_read == 'OPTNO') {
          $data['message_new'] = 'new';
        }
        $message_str = $message_str.'
        <a href="/chat" class="dropdown-item">
          <div class="dropdown-item-avatar">
            <img alt="image" src="'.asset('assets/img/avatar/avatar-1.png').'" class="rounded-circle">
          </div>
          <div class="dropdown-item-desc">
            '.$message->notification_message.'
            <div class="time text-primary">'.$message->created_at.'</div>
          </div>
        </a>
        ';
      }
    }
    $data['messages'] = $message_str;

    $data['notifications'] = Notification::with('users','users.store')
    ->where('id_users', $id)
    ->orderBy('created_at', 'DESC')
    ->where('info', 'notification')
    ->limit(5)
    ->get();

    $transaction_str = "";
    $data['notif_new'] = 'no';
    if ($data['notifications'] != "[]") {
      foreach ($data['notifications'] as $notif) {
        if ($notif->notification_read == 'OPTNO') {
          $data['notif_new'] = 'new';
        }
        if (Auth::user()->role == "ROLAD") {
          $transaction_str = $transaction_str.'
          <a href="/list-pesanan" class="dropdown-item">
            <div class="dropdown-item-desc">
              '.$notif->notification_message.'
              <div class="time text-primary">'.$notif->created_at.'</div>
            </div>
          </a>
          ';
        }else{
          $transaction_str = $transaction_str.'
          <a href="/mengelola-transaksi" class="dropdown-item">
            <div class="dropdown-item-desc">
              '.$notif->notification_message.'
              <div class="time text-primary">'.$notif->created_at.'</div>
            </div>
          </a>
          ';
        }
      }
    }
    $data['notifications'] = $transaction_str;

    return $data;

  }

}
