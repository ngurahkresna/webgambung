@extends('layouts.dashboard-layout')

@section('page', 'Message')

@push('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('content')

<div class="row">
  <div class="col-md-6 col-xl-6">
    <div class="card">
      <div class="card-header">
        <div class="input-group">
          <h4>Silahkan Pilih User</h4>
        </div>
      </div>
      <div class="card-body contacts_body">
        <ui class="contacts">
          @foreach($users as $user)
          @if($user->dari->id != Auth::user()->id)
          <li class="user" data-id="{{ $user->dari->id }}" data-name="{{ $user->dari->name }}">
            <div class="d-flex bd-highlight">
              <div class="img_cont">
                @if($user->dari->avatar == null)
                <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle user_img">
                @else
                <img src="{{ asset('assets/img/avatar/'.$user->dari->avatar) }}" class="rounded-circle user_img">
                @endif
              </div>
              <div class="user_info">
                <span>{{ $user->dari->name }}</span>
              </div>
            </div>
          </li>
          @endif
          @endforeach
        </ui>
      </div>
      <div class="card-footer"></div>
    </div>
  </div>
  <div class="col-md-6 col-xl-6 chat">
    <div class="card">
      <div class="card-header msg_head">
        <div class="d-flex bd-highlight">
          <div class="img_cont img-header">
            <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle user_img">
            <span class="online_icon"></span>
          </div>
          <div class="user_info" id="user_info">
            <span></span>
            <p></p>
          </div>
        </div>
      </div>
      <div class="card-body msg_card_body">
      </div>
      <div class="card-footer">
        <div class="input-group">
          <div class="input-group-append">
            <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
          </div>
          <textarea name="message" class="form-control type_msg" placeholder="Type your message..."></textarea>
          <div class="input-group-append" id="send">
            <span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">

var id;

function getMessage(id){
  $.ajax({
    url: "chat/pilih-user",
    method: "POST",
    data:{
      id: id,
    },
    success: function(result){
      console.log(result);
      $(".msg_card_body").empty();
      $(".msg_card_body").append(result['chat']);
      $(".msg_card_body").scrollTop($(".msg_card_body").height()*100000);
      $("#user_info p").text(result['total']+" Pesan");
    }
  })
}

$('.user').on('click', function(){
  $('.user').removeClass('active');
  $(this).addClass('active');
  var src = $('.user .img_cont img').attr('src');

  id = $(this).data('id');
  var name = $(this).data('name');
  $("#user_info span").text(name);
  $(".img-header img").attr('src', src);

  $(".msg_card_body").scrollTop($(".msg_card_body").height()*100000);

  setInterval(function() {
      getMessage(id);
  }, 1000);
});

$('#send').on('click', function(){
  var message = $("textarea[name='message']").val();
  sendMessage(id,message);
  getMessage(id);
  $(".msg_card_body").scrollTop($(".msg_card_body").height()*100000);
});

function sendMessage(id,message)
{
  $.ajax({
    url: "chat/send-message",
    method: "POST",
    data:{
      id: id,
      message: message,
    },
    success: function(result){
      console.log(result);
      $("textarea[name='message']").val("");
    }
  })
}

</script>
@endpush
