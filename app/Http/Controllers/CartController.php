<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Cart;
use Alert;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $username = Auth::user()->username;

        $data['carts'] = Cart::with(['product.store', 'cart_product_status', 'product.images' => function ($query) {
            $query->where('main_image', '=', 'OPTYS');
        }])->where('username', $username)->get();

        $shopping_charges = 0;
        foreach ($data['carts'] as $cart) {
            $shopping_charges += $cart->price;
        }

        $data['shopping_charges'] = $shopping_charges;
        return view('buyer.cart', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_code = $request->product_code;
        $quantity = $request->quant;
        $price = ($request->product_price * $quantity);
        $username = Auth::user()->username;
        $value = $request->ukuran;

        $data = Cart::with('cart_product_status')
            ->where('product_code', $product_code)
            ->where('username', $username)
            ->get();

        $status = Product::with('product_detail')
            ->where('code', $product_code)
            ->get();

        foreach ($data as $dt) {
            if ($dt->cart_product_status != null) {
                if ($dt->cart_product_status->value == $value) {
                    DB::table('tb_cart')
                        ->updateOrInsert(
                            [
                                'product_code' => $product_code,
                                'username' => $username,
                            ],
                            [
                                'quantity' => $quantity,
                                'price' => $price,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                    $id = DB::table('tb_cart')
                        ->where('product_code', $product_code)
                        ->where('username', $username)
                        ->orderBy('created_at', 'DESC')
                        ->get();

                    DB::table('cart_product_status')->updateOrInsert(
                        [
                            'id_cart' => $id[0]->id,
                            'status_code' => "STS01",
                        ],
                        [
                            'value' => $value,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    Alert::success('Berhasil', 'Berhasil menambahkan ke keranjang');
                    return redirect()->route('cart.index');
                }
            } else {
                DB::table('tb_cart')->updateOrInsert(
                    [
                        'product_code' => $product_code,
                        'username' => $username,
                    ],
                    [
                        'quantity' => $quantity,
                        'price' => $price,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                Alert::success('Berhasil', 'Berhasil menambahkan ke keranjang');
                return redirect()->route('cart.index');
            }
        }
        DB::table('tb_cart')->insert([
            'product_code' => $product_code,
            'username' => $username,
            'quantity' => $quantity,
            'price' => $price,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($value != null) {

            $id = DB::table('tb_cart')
                ->where('product_code', $product_code)
                ->where('username', $username)
                ->orderBy('created_at', 'DESC')
                ->get();

            DB::table('cart_product_status')->insert([
                'id_cart' => $id[0]->id,
                'status_code' => "STS01",
                'value' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
        Alert::success('Berhasil', 'Berhasil menambahkan ke keranjang');
        return redirect()->route('cart.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carts = json_decode($request->cart);
        $quantities = json_decode($request->quantity);
        $messages = json_decode($request->message);

        Cart::where('username', Auth::user()->username)
            ->update([
                'checkout_status' => 0,
            ]);

        $i = 0;

        foreach ($carts as $cart) {
            $price = Cart::with('product')
                ->where('id', $cart)->first();

            $total = ($price->product->price * $quantities[$i]);

            DB::table('tb_cart')
                ->where('id', $cart)
                ->update([
                    'quantity' => $quantities[$i],
                    'price' => $total,
                    'message' => $messages[$i],
                    'checkout_status' => 1,
                ]);

            $i++;
        }

        return redirect()->route('checkout');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('cart_product_status')->where('id_cart', $id)->delete();
        DB::table('tb_cart')->where('id', $id)->delete();
        Alert::success('Berhasil', 'berhasil menghapus produk');
        return redirect()->route('cart.index');
    }
}
