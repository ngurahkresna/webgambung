<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductDetail;
use App\Category;
use App\Review;
use App\Wishlist;
use App\TransactionDetail;
use App\TransactionHistory;
use Carbon\Carbon;
use Auth;
use Alert;
use DB;

class ProductController extends Controller
{
	public function __construct()
	{
		if (Auth::check()) {
			if(Auth::user()->role != 'ROLPB') {
				$this->middleware('auth');
			}
		}
	}

	public function index()
	{
		$data['categories'] = Category::with(['product.images' => function ($query) {
			$query->where('main_image', '=', 'OPTYS');
		}])->get();

		return view('buyer.produk', $data);
	}

	public function detail($product_code)
	{
		$data['product'] = Product::with('images','store','store.expedition','reviews','reviews.users','product_detail')
		->where('code', $product_code)->get();
		$data['products'] = Product::with(['images' => function ($query){
			$query->where('main_image', '=', 'OPTYS');
		}])
		->where('code', '!=', $product_code)
		->limit(4)
		->get();
		$wishlists = Wishlist::where('product_code', $product_code);
		$data['wishlists'] = $wishlists->count();
		$data['status_wishlist'] = false;

		if (Auth::check()) {
			$temp = $wishlists->where('id_users', Auth::user()->id)
			->get();
			if ($temp == "[]") {
				$data['status_wishlist'] = false;
			}else{
				$data['status_wishlist'] = true;
			}
		}

		$data['status_review'] = false;
		if (Auth::check()) {
			$temp = TransactionDetail::whereHas('transaction', function($query){
				$query->where('username', Auth::user()->username);
			})
			->where('product_code', $product_code)
			->get();

			if ($temp == "[]") {
				$data['status_review'] = false;
			}else{
				$data['status_review'] = true;
			}
		}

		$data['total_jual'] = TransactionDetail::where('product_code', $product_code)
		->sum('quantity')*100/100;

		return view('buyer.detail-produk', $data);
	}

	public function review(Request $request)
	{

		if(Auth::user()->role != 'ROLPB') {
			return redirect('/home');
		}

		$review = $request->review;
		$code = $request->code;
		$id_users = Auth::user()->id;

		Review::insert([
			'id_users' => $id_users,
			'product_code' => $code,
			'review' => $review,
			'created_at' => Carbon::now(),
		]);

		Alert::success('Berhasil', 'Berhasil menambahkan review');
		return redirect()->back();

	}

	public function search(Request $request){
		$key = $request->key;

		$data['categories'] = Category::with(['product.images' => function ($query) {
			$query->where('main_image', '=', 'OPTYS');
		},
		'product' => function($query) use($key){
			$query->where('name','like','%'.$key.'%')
			->orWhere('description','like','%'.$key.'%');
		}])
		->get();

		return view('buyer.produk', $data);

	}

	public function get_stock($product_code,$size)
	{

		$stock = ProductDetail::select('stock')
		->where('product_code', $product_code)
		->where('size', $size)
		->get();

		return $stock[0]->stock*100/100;

	}

}
