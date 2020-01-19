<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Auth;
use App\Wishlist;
use Alert;
use Carbon\Carbon;

class WishlistController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(){
    $data['categories'] = Category::with('product.wishlists')
    ->whereHas('product.wishlists.users', function($query){
      $query->where('id_users', Auth::user()->id);
    })
    ->get();

    return view('buyer.wishlist', $data);
  }

  public function proses_wishlist(Request $request)
  {

    $code = $request->code;
    $id_users = Auth::user()->id;

    $data = Wishlist::where('id_users', $id_users)
    ->where('product_code', $code)
    ->get();

    if ($data == "[]") {
      Wishlist::insert([
        'id_users' => $id_users,
        'product_code' => $code,
        'created_at' => Carbon::now(),
      ]);
      Alert::success('Berhasil', 'Berhasil ditambahkan ke wishlist');
    }else{
      Wishlist::where('id_users', $id_users)
      ->where('product_code', $code)
      ->delete();
      Alert::success('Berhasil', 'Berhasil menghapus dari wishlist');
    }

    return redirect()->back();

  }
}
