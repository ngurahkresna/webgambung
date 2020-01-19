<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use User;
use Auth;
use Alert;
use Carbon\Carbon;
use App\Store;
use App\Notification;

class ChatController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $id = Auth::user()->id;

    $data = Message::with('dari','dari.store')
    ->select('from_user')
    ->where('to_user', $id)
    ->groupBy('from_user')
    ->orderBy('created_at', 'DESC')
    ->get();

    if (Auth::user()->role == 'ROLPJ') {
      return view('seller.chat', ['users' => $data]);
    }elseif(Auth::user()->role == 'ROLPB'){
      return view('buyer.chat', ['users' => $data]);
    }else{
      return redirect('/');
    }

  }

  public function pilih_user(Request $request){

    $id = Auth::user()->id;
    $id_tujuan = $request->id;

    $data = Message::with('tujuan')
    ->where(function($q) use ($id, $id_tujuan){
      $q->where('from_user', $id)->Where('to_user', $id_tujuan);
    })
    ->orWhere(function($query) use ($id, $id_tujuan){
      $query->where('from_user', $id_tujuan)->Where('to_user', $id);
    });

    $chats = $data->get();
    $total = $data->count();

    //return html untuk append di chat box
    $parse = "";
    foreach ($chats as $chat) {
      if ($chat->from_user == $id_tujuan) {
        if ($chat->dari->avatar == null) {
          $parse = $parse.
          '<div class="d-flex justify-content-start mb-4">
            <div class="img_cont_msg">
              <img src="/assets/img/avatar/avatar-1.png" class="rounded-circle user_img_msg">
            </div>
            <div class="msg_cotainer bg-light">
              '.$chat->message.'
              <span class="msg_time text-primary" style="width:100px">'.$chat->created_at.'</span>
            </div>
          </div>';
        }else{
            $parse = $parse.
            '<div class="d-flex justify-content-start mb-4">
              <div class="img_cont_msg">
                <img src="/assets/img/avatar'.$chat->dari->avatar.'" class="rounded-circle user_img_msg">
              </div>
              <div class="msg_cotainer bg-light">
                '.$chat->message.'
                <span class="msg_time text-primary" style="width:100px">'.$chat->created_at.'</span>
              </div>
            </div>';
          }
      }else{
        if ($chat->dari->avatar == null) {
          $parse = $parse.
          '<div class="d-flex justify-content-end mb-4">
            <div class="msg_cotainer_send bg-light">
              '.$chat->message.'
              <span class="msg_time_send text-primary" style="width:100px">'.$chat->created_at.'</span>
            </div>
            <div class="img_cont_msg">
              <img src="/assets/img/avatar/avatar-2.png" class="rounded-circle user_img_msg">
            </div>
          </div>';
        }else{
          $parse = $parse.
          '<div class="d-flex justify-content-end mb-4">
            <div class="msg_cotainer_send bg-light">
              '.$chat->message.'
              <span class="msg_time_send text-primary" style="width:100px">'.$chat->created_at.'</span>
            </div>
            <div class="img_cont_msg">
              <img src="/assets/img/avatar'.$chat->dari->avatar.'" class="rounded-circle user_img_msg">
            </div>
          </div>';
        }
      }
    }

    return ['chat' => $parse, 'total'=>$total];

  }

  public function send_message(Request $request)
  {
    $user = Auth::user()->id;
    $id = $request->id;
    $message = $request->message;

    if ($message == null || $message == "") {
      Alert::error('Gagal', 'Pesan tidak boleh kosong!');
      return;
    }

    Message::insert([
      'to_user' => $id,
      'from_user' => $user,
      'message' => $message,
      'created_at' => Carbon::now(),
    ]);

    Notification::insert([
      'id_users' => $id,
      'notification_from' => $user,
      'notification_message' => 'Mengirim anda pesan.',
      'info' => 'message',
      'notification_read' => 'OPTNO',
      'created_at' => Carbon::now(),
    ]);

    return ['status' => 'terkirim'];

  }

  public function hubungi_toko(Request $request){

    $code = $request->code;
    $id_penjual = Store::with('users')
    ->where('code', $code)
    ->get();
    $id_penjual = $id_penjual[0]->users->id;

    Message::insert([
      'to_user' => Auth::user()->id,
      'from_user' => $id_penjual,
      'message' => "Selamat datang, ada yang bisa kami bantu ?",
      'created_at' => Carbon::now(),
    ]);

    return redirect('/chat');

  }

}
