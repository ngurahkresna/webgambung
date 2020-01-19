<?php

namespace App\Http\Controllers;

use App\TransactionDetail;
use App\TransactionHistory;
use App\TransactionPayment;
use Illuminate\Http\Request;
use App\Notification;
use Auth;
use Carbon\Carbon;
use Alert;
use App\Transaction;

class TransaksiController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $transaction = Transaction::with(['detail.product.store', 'payment', 'users'])
    ->get();

    foreach ($transaction as $trans) {
      $trans->isoverdue = ($trans->payment['deadline_proof'] < \Carbon\Carbon::now()) ? 'OPTYS' : 'OPTNO';

      $detail_cancel_buyer = TransactionDetail::where([
        ['transaction_code', '=', $trans->code],
        ['shipping_status', '=', 'OPTCC'],
        ])->count();

        $detail_cancel_admin = TransactionDetail::where([
          ['transaction_code', '=', $trans->code],
          ['shipping_status', '=', 'OPTAC'],
          ])->count();

          $trans->isCancelledBuyer = ($detail_cancel_buyer > 0) ? 'OPTYS' : 'OPTNO';
          $trans->isCancelledAdmin = ($detail_cancel_admin > 0) ? 'OPTYS' : 'OPTNO';
        }

        return view('admin.transaksi', ['transaction' => $transaction]);
      }

      public function get_detail_transaction(Request $request)
      {
        $data = Transaction::with(['detail.product.store', 'users'])->where('code', $request->code)->first();
        echo json_encode($data);
      }

      public function get_proof_transaction(Request $request)
      {
        $data = TransactionPayment::where('transaction_code', $request->code)->first();
        echo json_encode($data);
      }

      public function verification(Request $request)
      {
        $code = $request->transaction_code;

        TransactionPayment::where('transaction_code', $code)->update([
            'verified_status' => 'OPTYS',
            'verified_date' => Carbon::now(),
            'updated_by' => Auth::user()->username,
            'updated_at' => Carbon::now(),
            'updated_process' => 'pengiriman'
        ]);

        $id = Transaction::where('code', $code)
        ->get();

        $transaction = Transaction::with('detail')
        ->where('code', $code)
        ->get();

        Notification::insert([
          'id_users' => $id[0]->users->id,
          'notification_message' => "Transaksi ".$code." sudah masuk tahap pengiriman.",
          'info' => 'notification',
          'notification_read' => 'OPTNO',
          'created_at' => Carbon::now(),
        ]);

        foreach ($transaction[0]->detail as $detail) {

          Notification::insert([
            'id_users' => $detail->product->store->users->id,
            'notification_message' => "Transaksi ".$code." sudah dikonfimasi admin",
            'info' => 'notification',
            'notification_read' => 'OPTNO',
            'created_at' => Carbon::now(),
          ]);

        }

        Alert::success('Berhasil', 'Berhasil Konfirmasi Transaksi');
        return redirect()->route('transaction.index');
      }

      public function transaction_list()
      {
        $transactions = TransactionDetail::with('transaction.users', 'product.store')->whereHas('product.store', function ($q) {
          $q->where('username', Auth::user()->username);
        })->whereHas('transaction.payment', function ($q) {
          $q->where('verified_status', 'OPTYS');
        })->get();

        return view('seller.pesanan', compact('transactions'));
      }

      public function verification_delivery(Request $request)
      {
        $code = $request->transaction_code;

        TransactionDetail::where('transaction_code', $code)->update([
          'shipping_status' => 'OPTSD',
          'shipping_no' => $request->shipping_no,
          'updated_by' => Auth::user()->username,
          'updated_at' => Carbon::now()
        ]);

        $id = Transaction::where('code', $code)
        ->get();

        Notification::insert([
          'id_users' => $id[0]->users->id,
          'notification_message' => "Transaksi ".$code." sudah masuk dikirim, jangan lupa menekan tombol diterima jika sudah sampai.",
          'info' => 'notification',
          'notification_read' => 'OPTNO',
          'created_at' => Carbon::now(),
        ]);

        Alert::success('Berhasil', 'Berhasil Konfirmasi Pengiriman');
        return redirect()->route('transaction.list');
      }

      public function cancel(Request $request)
      {
        $code = $request->transaction_code;

        TransactionDetail::where('transaction_code', $code)->update([
          'shipping_status' => 'OPTAC',
          'updated_by' => Auth::user()->username,
          'updated_at' => Carbon::now(),
        ]);

        $details = TransactionDetail::where('transaction_code', $code)->get();
        foreach ($details as $detail) {
          TransactionHistory::insert([
            'transaction_code' => $code,
            'product_code' => $detail->product_code,
            'status' => 'overdue',
            'created_by' => Auth::user()->username,
            'updated_by' => Auth::user()->username,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ]);
        }

        Transaction::where('code', $code)->update([
          'total_product' => 0,
          'total_quantity' => 0,
          'total_weight' => 0,
          'shipping_charges' => 0,
          'total_amount' => 0,
          'discount_pct' => 0,
          'discount_amount' => 0,
          'grand_total_amount' => 0,
          'updated_by' => Auth::user()->username,
          'updated_at' => Carbon::now()
        ]);

        Alert::success('Berhasil', 'Berhasil Melakukan Pembatalan');
        return redirect()->route('transaction.index');
      }
    }
