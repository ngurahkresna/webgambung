<?php

namespace App\Http\Controllers;

use App\StatusCategory;
use App\StatusDetail;
use App\Transaction;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Product;
use App\ProductDetail;
use App\ProductImages;
use App\Category;
use App\Store;
use Alert;
use DB;
use Auth;

class MengelolaProdukController extends Controller
{
    private $masterdata;

    public function __construct()
    {
        $this->middleware('auth');
        $this->masterdata = new MasterdataController();
    }

    public function index()
    {
        if (Auth::user()->role == 'ROLPJ') {
            $data['produk'] = Product::with('images', 'store.users')
                ->whereHas('store.users', function ($query) {
                    $query->where('id', Auth::user()->id);
                })
                ->orderBy('id', 'DESC')
                ->get();

            $data['categories'] = Category::all();
            $data['sizes'] = StatusDetail::where('status_code', 'STS01')->get();

            return view('seller.mengelolaproduk', $data);
        } else {
            $data['produk'] = Product::with('images')
                ->orderBy('id', 'DESC')
                ->get();
            $data['categories'] = Category::all();
            $data['stores'] = Store::all();
            $data['sizes'] = StatusDetail::where('status_code', 'STS01')->get();

            return view('admin.mengelolaproduk', $data);
        }

    }

    public function delete(Request $request)
    {
        $code = $request->code;

        $transactions = TransactionDetail::where('product_code', $code)->get();
        if (count($transactions) > 0) {
            Alert::warning('Gagal', 'Produk Telah Memiliki Transaksi');
        } else {
            DB::table('tb_product_images')->where('product_code', $code)->delete();
            DB::table('tb_product_detail')->where('product_code', $code)->delete();
            DB::table('tb_product')->where('code', $code)->delete();

            Alert::success('Berhasil', 'Berhasil menghapus data produk');
        }

        if (Auth::user()->role == 'ROLPJ') {
            return redirect('/mengelola-produk');
        } else {
            return redirect('/mengelola-produk-admin');
        }
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'foto' => 'required',
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'toko' => 'sometimes|required',
            'kategori' => 'required',
            'berat' => 'required|numeric',
            'harga' => 'required|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'total_stock' => 'required|numeric',
            'warna' => 'sometimes|required',
            'size.*' => 'sometimes|required|distinct',
            'stock_size.*' => 'sometimes|required|numeric',
        ]);

        //pengecekan apakah yang upload penjual
        if (Auth::user()->role != 'ROLPJ') {
            $request->validate([
                'toko' => 'required',
            ]);
        }

        $file = $request->file('foto');
        $nama_produk = $request->nama_produk;
        $deskripsi = $request->deskripsi;
        $kategori = $request->kategori;
        $berat = $request->berat;
        $harga = $request->harga;
        $warna = $request->warna;
        $panjang = $request->length ?? 0;
        $lebar = $request->width ?? 0;
        $tinggi = $request->height ?? 0;
        $size = $request->size;
        $stock_size = $request->stock_size;
        $total_stock = $request->total_stock ?? 0;

        //pengecekan apakah yang upload penjual
        if (Auth::user()->role == 'ROLPJ') {
            $data = Store::with('users')
                ->select('code')
                ->whereHas('users', function ($query) {
                    $query->where('id', Auth::user()->id);
                })
                ->get();

            if ($data == "[]") {
                Alert::error('Gagal', 'Anda belum membuat toko, silahkan hubungi admin untuk proses pembuatan toko');
                redirect('/mengelola-produk');
            } else {
                $toko = $data[0]->code;
            }

        } else {
            $toko = $request->toko;
        }


        $nama_file = rand() . $file[0]->getClientOriginalName();
        $file[0]->move(public_path().'/assets/img/products', $nama_file);

        $code = $this->masterdata->getProductSequence();

        Product::insert([
            'code' => $code,
            'store_code' => $toko,
            'name' => $nama_produk,
            'main_category' => $kategori,
            'description' => $deskripsi,
            'weight' => $berat,
            'stock' => $total_stock,
            'price' => $harga,
            'color' => $warna,
            'width' => $lebar,
            'height' => $tinggi,
            'length' => $panjang,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        ProductImages::insert([
            'product_code' => $code,
            'image_name' => '/' . $nama_file,
            'main_image' => 'OPTYS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($size != null) {
            for ($i = 0; $i < count($size); $i++) {
                ProductDetail::insert([
                    'product_code' => $code,
                    'size' => $size[$i],
                    'stock' => $stock_size[$i],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil menambahkan data produk');

        if (Auth::user()->role == 'ROLPJ') {
            return redirect('/mengelola-produk');
        } else {
            return redirect('/mengelola-produk-admin');
        }
    }

    private function incrementCode()
    {
        $code = DB::table('tb_product')
            ->select('code')
            ->orderBy('id', 'DESC')
            ->first();

        if ($code == null) {
            return 'PRD0000000';
        } else {
            return $code->code;
        }
    }

    public function get(Request $request)
    {
        $id = $request->id;
        $produk = Product::with('images', 'main_category', 'product_detail')
            ->where('id', $id)
            ->first();

        return $produk;
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'toko' => 'sometimes|required',
            'kategori' => 'required',
            'berat' => 'required|numeric',
            'harga' => 'required|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'total_stock' => 'required|numeric',
            'warna' => 'sometimes|required',
            'size.*' => 'sometimes|required|distinct',
            'stock_size.*' => 'sometimes|required|numeric',
        ]);

        //pengecekan apakah yang upload penjual
        if (Auth::user()->role != 'ROLPJ') {
            $request->validate([
                'toko' => 'required',
            ]);
        }

        $nama_produk = $request->nama_produk;
        $deskripsi = $request->deskripsi;
        $kategori = $request->kategori;
        $berat = $request->berat;
        $harga = $request->harga;
        $warna = $request->warna;
        $panjang = $request->length ?? 0;
        $lebar = $request->width ?? 0;
        $tinggi = $request->height ?? 0;
        $size = $request->size;
        $stock_size = $request->stock_size;
        $total_stock = $request->total_stock ?? 0;

        //pengecekan apakah yang upload penjual
        if (Auth::user()->role == 'ROLPJ') {
            $data = Store::with('users')
                ->select('code')
                ->whereHas('users', function ($query) {
                    $query->where('id', Auth::user()->id);
                })
                ->get();
            $toko = $data[0]->code;

        } else {
            $toko = $request->toko;
        }

        Product::where('code', $request->code)
            ->update([
                'store_code' => $toko,
                'name' => $nama_produk,
                'main_category' => $kategori,
                'description' => $deskripsi,
                'weight' => $berat,
                'stock' => $total_stock,
                'price' => $harga,
                'color' => $warna,
                'width' => $lebar,
                'height' => $tinggi,
                'length' => $panjang,
                'updated_at' => Carbon::now(),
            ]);

        ProductDetail::where('product_code', $request->code)->delete();
        if ($size != null) {
            for ($i = 0; $i < count($size); $i++) {
                ProductDetail::insert([
                    'product_code' => $request->code,
                    'size' => $size[$i],
                    'stock' => $stock_size[$i],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil mengubah data produk');
        if (Auth::user()->role == 'ROLPJ') {
            return redirect('/mengelola-produk');
        } else {
            return redirect('/mengelola-produk-admin');
        }

    }

    public function add_images(Request $request)
    {
        $file = $request->file('foto');
        $id = $request->id;

        $nama_file = rand() . $file[0]->getClientOriginalName();
        $file[0]->move(public_path().'/assets/img/products', $nama_file);

        $code = DB::table('tb_product')
            ->select('code')
            ->where('id', $id)
            ->get();

        $jumlah = ProductImages::where('product_code', $code[0]->code)
            ->count();
        if ($jumlah == 5) {
            Alert::error('Gagal', 'Foto sudah maksimal');
            return redirect('/mengelola-produk-admin');
        }

        ProductImages::insert([
            'product_code' => $code[0]->code,
            'image_name' => '/' . $nama_file,
            'main_image' => 'OPTNO',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Alert::success('Berhasil', 'Berhasil menambahkan foto');
        if (Auth::user()->role == 'ROLPJ') {
            return redirect('/mengelola-produk');
        } else {
            return redirect('/mengelola-produk-admin');
        }

    }

    public function delete_images(Request $request)
    {
        $id = $request->id;

        ProductImages::where('id', $id)->delete();

        Alert::success('Berhasil', 'Berhasil menghapus foto');
        if (Auth::user()->role == 'ROLPJ') {
            return redirect('/mengelola-produk');
        } else {
            return redirect('/mengelola-produk-admin');
        }

    }

    public function get_category_status(Request $request)
    {
        $data = StatusCategory::with('status')->where('category_code', $request->category)->get();

        echo json_encode($data);
    }

    public function search(Request $request)
    {
        $value = $request->value;
        if (Auth::user()->role == 'ROLPJ') {
            $data = Product::with('images', 'store.users')
                ->whereHas('store.users', function ($query) {
                    $query->where('id', Auth::user()->id);
                })
                ->where([
                    ['name', 'LIKE', "%{$value}%"]
                ])
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $data = Product::with('images')
                ->orderBy('id', 'DESC')
                ->where([
                    ['name', 'LIKE', "%{$value}%"]
                ])
                ->get();
        }

        return response()->json($data);
    }

}
