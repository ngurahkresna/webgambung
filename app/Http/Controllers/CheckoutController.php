<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use Carbon\Carbon;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionPayment;
use App\Product;
use App\ProductDetail;
use App\CartProductStatus;
use App\Voucher;
use App\Store;
use App\Message;
use App\Notification;
use App\Cart;
use App\TransactionDetailStatus;
use GuzzleHttp\Client;
use App\Http\Controllers\OngkirController;

class CheckoutController extends Controller
{
  private $ongkir;
  public function __construct()
  {
    $this->middleware('auth');
    $this->ongkir = new OngkirController();
  }

  public function fill_form()
  {

    $username = Auth::user()->username;

    $data['user'] = Auth::user();
    $data['stores'] = Store::with('product.cart','expedition')
    ->whereHas('product.cart', function($query) use($username){
      $query->where('checkout_status',1)->where('username', $username);;
    })
    ->get();

    $cities = $this->ongkir->getAllCities();
    $data['cities'] = $cities;

    $data['harga'] = 0;
    foreach ($data['stores'] as $store) {
      $data['weight'][$store->id] = 0;
      foreach ($store->product as $product) {
        foreach ($product->cart as $cart) {
          if ($cart->username == $username) {
            $data['harga'] += $cart->price;
            $data['weight'][$store->id] += ($product->weight * $cart->quantity);
          }
        }
      }
    }

    if ($data['stores'] == "[]") {
      Alert::error('Gagal', 'Silahkan memberi barang terlebih dahulu');
      return redirect('/produk');
    }

    return view('buyer.checkout', $data);
  }

  //cashback belum dimasukan ke database
  public function check_voucher(Request $request)
  {
    $code = $request->code;

    $dateNow = Carbon::now();
    $voucher = Voucher::with('terms')
    ->where('code', $code)
    ->whereDate('start_date', '<=', $dateNow)
    ->whereDate('end_date', '>=', $dateNow)
    ->get();

    if ($voucher == "[]") {
      return ['status'=>'wrong code'];
    }

    $carts = Cart::with('product','product.store.voucher')
    ->where('checkout_status', 1)
    ->where('username', Auth::user()->username)
    ->get();

    $price = 0;
    $stores = "";
    foreach ($carts as $cart) {
      foreach ($cart->product->store->voucher as $vouchers) {
        if ($vouchers->voucher_code == strtoupper($code)) {
          $stores = $cart->product->store->name.", ";
          $price += (int)$cart->price;
        }
      }
    }

    $diskon = ($voucher[0]->percentage * $price / 100);
    if ($diskon > $voucher[0]->max_price) {
      return ['status'=>'wrong terms'];
    }

    $tipeVoucher = $voucher[0]->type;

    $data['status'] = "correct";
    $data['voucher'] = $diskon;
    $data['stores'] = $stores;
    $data['percentage'] = $voucher[0]->percentage*100/100;
    if ($tipeVoucher == "VCRDS") {
      $data['tipe'] = "diskon";
    }else{
      $data['tipe'] = "cashback";
      $data['voucher'] = 0;
    }
    return $data;

  }

  public function get_expedition(Request $request)
  {

    $city_id = $request->city;

    $data['stores'] = Store::with([
      'product.cart' => function ($query) {
        $query->where('username', '=', Auth::user()->username);
      },
      'expedition',
    ])
    ->has('product.cart')->get();

    //get weight each store
    foreach ($data['stores'] as $store) {
      $data['weight'][$store->id] = 0;
      foreach ($store->product as $product) {
        foreach ($product->cart as $cart) {
          $data['weight'][$store->id] += ($product->weight * $cart->quantity);
        }
      }
    }

    $i = 0;
    foreach ($data['stores'] as $store) {
      $j = 0;
      $data['expeditions'][$i]['id'] = $store->id;
      foreach ($store->expedition as $exp) {
        $data['expeditions'][$i]['expedition'][$j] = $this->ongkir->getPrice($city_id,$data['weight'][$store->id],$exp->expedition_code);
        $j++;
      }
      $i++;
    }

    return $data['expeditions'];

  }

  //proses checkout
  public function proses_checkout(Request $request){
    $seq = new MasterdataController();
    $trans_code = $seq->getTransactionSequence();
    $username = Auth::user()->username;
    $user_id = Auth::user()->id;
    $address = $request->address;
    $phone = $request->phone;
    $voucher_code = strtoupper($request->voucher);
    $pct = 0;
    if ($request->status_voucher == "invalid") {
      $voucher_code = null;
    }else{
      $pct = Voucher::where('code', $voucher_code)
      ->get();
      $pct = $pct[0]->percentage;
    }

    $total_ekspedisi = $request->total_ekspedisi;
    $total_produk = $request->total_produk;
    $total_discount = $request->total_diskon;
    $grand_total = $request->grand_total;

    $checkouts = Cart::with('product','product.store')
    ->where('checkout_status', 1)
    ->where('username', $username);

    $jumlahProduk = $checkouts->count();
    $jumlahQuantity = $checkouts->get()->sum('quantity');
    $totalWeight = 0;
    foreach($checkouts->get() as $checkout){
      $totalWeight += ($checkout->quantity * $checkout->product->weight);
    }
    if ($totalWeight < 1) {
      $totalWeight = 1;
    }

    //insert master data transaction from checkout
    Transaction::insert([
      'code' => $trans_code,
      'username' => $username,
      'address_1' => $address,
      'phone' => $phone,
      'total_product' => $jumlahProduk,
      'total_weight' => $totalWeight,
      'total_quantity' => $jumlahQuantity,
      'voucher_code' => $voucher_code,
      'shipping_charges' => $total_ekspedisi,
      'total_amount' => $total_produk,
      'discount_pct' => $pct,
      'discount_amount' => $total_discount,
      'grand_total_amount' => $grand_total,
      'created_by' => Auth::user()->username,
      'updated_by' => Auth::user()->username,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    //insert each transaction detail from checkout
    $store_ids = $request->store_id;
    $expedition_stores = $request->expedition;
    foreach($checkouts->get() as $checkout) {

      //check product code berada di toko yang mana
      $product_code = $checkout->product_code;
      $product_store_id = $checkout->product->store->id;
      for ($i=0; $i < count($store_ids); $i++) {
        if ($store_ids[$i] == $product_store_id) {
          $expedition = $expedition_stores[$i];
        }
      }

      TransactionDetail::insert([
        'transaction_code' => $trans_code,
        'product_code' => $product_code,
        'expedition' => $expedition, //edit
        'quantity' => $checkout->quantity,
        'weight' => ($checkout->quantity*$checkout->product->weight),
        'price' => $checkout->price,
        'message' => $checkout->message,
        'created_by' => Auth::user()->username,
        'updated_by' => Auth::user()->username,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
    }

    $detail_id = TransactionDetail::where('transaction_code', $trans_code)->first();

    //insert into payment transaction
    TransactionPayment::insert([
      'transaction_code' => $trans_code,
      'deadline_proof' => Carbon::now()->add(1, 'day'),
      'created_by' => Auth::user()->username,
      'updated_by' => Auth::user()->username,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
      'updated_process' => 'pembayaran',
    ]);

    //updated stock product and delete cart
    foreach($checkouts->get() as $checkout) {
      $products = Product::where('code', $checkout->product_code);
      $current_quantity = $products->select('stock')->get();
      $updated_quantity = $current_quantity[0]->stock - $checkout->quantity;

      $products->update([
        'stock' => $updated_quantity,
        'updated_at' => Carbon::now(),
      ]);

      if (isset($checkout->cart_product_status)) {
        $detail = ProductDetail::where('product_code', $checkout->product_code)
        ->where('size', $checkout->cart_product_status->value);

        $current_quantity = $detail->select('stock')->get();
        $updated_quantity = $current_quantity[0]->stock - $checkout->quantity;

        $detail->update([
          'stock' => $updated_quantity,
          'updated_at' => Carbon::now(),
        ]);
      }
    }

    foreach($checkouts->get() as $checkout){
      if (isset($checkout->cart_product_status)) {
        TransactionDetailStatus::insert([
          'id_detail' => $detail_id->id,
          'status_code' => $checkout->cart_product_status->status_code,
          'value' => $checkout->cart_product_status->value,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]);
        CartProductStatus::where('id_cart', $checkout->id)->delete();
      }
    }

    //chat ke penjual
    foreach($checkouts->get() as $checkout){
      $id_toko =  $checkout->product->store->users->id;

      Message::insert([
        'to_user' => $id_toko,
        'from_user' => $user_id,
        'message' => 'Hai, saya telah membeli produk '.$checkout->product->name.', sebanyak '.$checkout->quantity.' Terima kasih',
        'created_at' => Carbon::now(),
      ]);

      Notification::insert([
        'id_users' => $id_toko,
        'notification_from' => $user_id,
        'notification_message' => $username.' Mengirim anda pesan.',
        'info' => 'message',
        'notification_read' => 'OPTNO',
        'created_at' => Carbon::now(),
      ]);

      Notification::insert([
        'id_users' => $user_id,
        'notification_message' => "Pembelian ".$checkout->product->name." sudah masuk tahap pembayaran.",
        'info' => 'notification',
        'notification_read' => 'OPTNO',
        'created_at' => Carbon::now(),
      ]);
    }

    //input notifikasi
    foreach($checkouts->get() as $checkout){

    }

    $checkouts->delete();

    Alert::success('Berhasil', 'Berhasil memesan, silahkan lakukan pembayaran');
    return redirect("/bayar/".$trans_code);
  }


  public function get_city($province)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $province,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "key: " . config('api_key')
      ),
    ));

    $response = json_decode(curl_exec($curl));
    $err = curl_error($curl);

    curl_close($curl);

    echo json_encode($response->rajaongkir->results);
  }

  public function get_price(Request $request)
  {
    $origin = '22';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "origin=" . $origin . "&destination=" . $request->city . "&weight=" . $request->weight . "&courier=" . $request->expedition . "",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: " . config('api_key')
      ),
    ));

    $response = json_decode(curl_exec($curl));
    $err = curl_error($curl);

    curl_close($curl);

    dd($response);

    echo json_encode($response->rajaongkir->results);
  }
}
