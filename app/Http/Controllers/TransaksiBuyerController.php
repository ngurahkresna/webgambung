<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use Carbon\Carbon;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionHistory;
use App\Product;
use App\ProductDetail;

class TransaksiBuyerController extends Controller
{

  private $ongkir;

  public function __construct()
  {
    $this->middleware('auth');
    $this->ongkir = new OngkirController;
  }

  public function index()
  {
    $data['transactions'] = Transaction::with('detail','payment','detail.product.store','detail.product.images', 'history','detail.status')
    ->where('username', Auth::user()->username)
    ->get();

    return view('buyer.transaksi', $data);
  }

  public function terima(Request $request){
    $transaction_code = $request->transaction_code;
    $product_code = $request->product_code;

    TransactionDetail::where('transaction_code', $transaction_code)
    ->where('product_code', $product_code)
    ->update([
      'shipping_status' => 'OPTRC',
      'updated_at' => Carbon::now(),
      'updated_by' => Auth::user()->username,
    ]);

    TransactionHistory::insert([
      'transaction_code' => $transaction_code,
      'product_code' => $product_code,
      'status' => 'accepted',
      'created_by' => Carbon::now(),
      'updated_by' => Carbon::now(),
    ]);

    Alert::success('Berhasil', 'Transaksi sudah diterima');
    return redirect('/transaksi');

  }

  public function cancel(Request $request)
  {
    $transaction_code = $request->transaction_code;
    $product_code = $request->product_code;

    $details = TransactionDetail::where('transaction_code', $transaction_code)
    ->where('product_code', $product_code);

    $products = $details->get();
    if ($products[0]->product->product_detail == null) {
      Product::where('code', $product_code)
      ->increment('stock', $products[0]->quantity);
    }else{
      $temp = Product::where('code', $product_code);
      $get_product = $temp->get();
      $status = $get_product[0];
      foreach ($status->product_detail as $detail) {
        if ($detail->size == $products[0]->status->value) {
          ProductDetail::where('product_code', $product_code)
          ->where('size', $detail->size)
          ->increment('stock', $products[0]->quantity);
        }
      }
      $temp->increment('stock', $products[0]->quantity);
    }

    $details->update([
      'shipping_status' => 'OPTCC',
      'updated_at' => Carbon::now(),
      'updated_by' => Auth::user()->username,
    ]);

    TransactionHistory::insert([
      'transaction_code' => $transaction_code,
      'product_code' => $product_code,
      'status' => 'canceled',
      'created_by' => Auth::user()->username,
      'updated_by' => Auth::user()->username,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    $check = TransactionDetail::where('transaction_code', $transaction_code)
    ->where('shipping_status', '!=', 'OPTCC');

    $products = $check->get();

    // return $products[0]->transaction->users->city;

    if ($check->count() > 0) {
      $totalProduct = $products->count();
      $totalQuantity = $products->sum('quantity');
      $totalAmount = $products->sum('price');
      $totalWeight = $products->sum('weight');

      //get new voucher
      $voucher_code = $products[0]->transaction->voucher_code;
      if ($voucher_code != null) {
        $max_price = $products[0]->transaction->voucher->max_price;
        $discount_pct = $products[0]->transaction->discount_pct;
        $diskon = ($discount_pct * $totalAmount / 100);
        if ($diskon > $max_price) {
          $voucher_code = null;
          $diskon = 0;
          $discount_pct = 0;
        }

        //get new expedition
        $expedition = $products[0]->expedition;
        $exp[] = explode(' ', $expedition);
        $ket = array_pop($exp[0]);
        array_pop($exp[0]);
        $exp = substr(array_pop($exp[0]), 1, 3);
        $city_id = $products[0]->transaction->users->city;
        $cost = $this->ongkir->getPrice($city_id,$totalWeight,strtolower($exp));
        $totalExpedisi = 0;
        foreach ($cost[0]['costs'] as $detail) {
          if ($detail['service'] == $ket) {
            $totalExpedisi = $detail['cost'][0]['value'];
          }
        }
        $grand_total = $totalAmount - $diskon + $totalExpedisi;

        Transaction::where('code', $transaction_code)
        ->update([
          'total_product' => $totalProduct,
          'total_weight' => $totalWeight,
          'total_quantity' => $totalQuantity,
          'voucher_code' => $voucher_code,
          'shipping_charges' => $totalExpedisi,
          'total_amount' => $totalAmount,
          'discount_pct' => $discount_pct,
          'discount_amount' => $diskon,
          'grand_total_amount' => $grand_total,
          'updated_by' => Auth::user()->username,
          'updated_at' => Carbon::now(),
        ]);
      }

    }else{
      Transaction::where('code', $transaction_code)
      ->update([
        'total_product' => 0,
        'total_weight' => 0,
        'total_quantity' => 0,
        'voucher_code' => null,
        'shipping_charges' => 0,
        'total_amount' => 0,
        'discount_pct' => 0,
        'discount_amount' => 0,
        'grand_total_amount' => 0,
        'updated_by' => Auth::user()->username,
        'updated_at' => Carbon::now(),
      ]);
    }

    Alert::success('Berhasil', 'Transaksi berhasil di-cancel');
    return redirect('/transaksi');
  }

}
