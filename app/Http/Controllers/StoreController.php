<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreExpedition;
use App\Store;
use DB;

class StoreController extends Controller
{

    public function index($store_code){
      $data['store'] = Store::with('expedition', 'product')
      ->where('code', $store_code)
      ->get();

      return view('buyer.toko', $data);

    }

    public function percobaan()
    {
    	// $store = Store::with('expedition')->get();

    	$store = StoreExpedition::with('store')->get();
    	$expedition = StoreExpedition::with('expedition')->get();

    	print_r('<pre>' . print_r($store, true) . '</pre>');
    	print_r('<pre>' . print_r($expedition, true) . '</pre>');
    }
}
