<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\MasterdataController;
use App\Category;
use DB;
use Alert;

class KategoriController extends Controller
{

    protected $seq;

    public function __construct()
    {
        $this->middleware('auth');
        $this->seq = new MasterdataController();
    }

    public function index()
    {
        $kategori = Category::all();

        return view('admin.kategori', ['kategori' => $kategori]);
    }

    public function delete(Request $request)
    {
        $code = $request->code;

        DB::table('tb_status_category')->where('category_code', $code)->delete();
        DB::table('tb_category')->where('code', $code)->delete();
        Alert::success('Berhasil', 'Berhasil menghapus data kategori');

        return redirect('/mengelola-kategori');

    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:tb_category,name|max:255',
            'action' => 'required'
        ]);

        $nama_kategori = $request->nama_kategori;

        $code = $this->seq->getMainCategorySequence();

        DB::table('tb_category')->insert([
            'code' => $code,
            'name' => $nama_kategori,
            'level' => 'Main Category',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $status = $request->status;
        if (isset($status)) {
            foreach ($status as $value) {
                DB::table('tb_status_category')->insert([
                    'category_code' => $code,
                    'status_code' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil menambahkan data kategori');
        return redirect('/mengelola-kategori');

    }

    public function get(Request $request)
    {
        $id = $request->id;
        $category = Category::with('status')->where('id', $id)->first();

        return $category;
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:255|unique:tb_category,name,' . $request->code . ',code',
            'action' => 'required'
        ]);

        $code = $request->code;
        $nama_kategori = $request->nama_kategori;

        Category::where('code', $code)
            ->update([
                'name' => $nama_kategori,
                'updated_at' => Carbon::now(),
            ]);

        DB::table('tb_status_category')->where('category_code', $code)->delete();

        $status = $request->status;
        if (isset($status)) {
            foreach ($status as $value) {
                DB::table('tb_status_category')->insert([
                    'category_code' => $code,
                    'status_code' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        Alert::success('Berhasil', 'Berhasil mengubah data admin');
        return redirect('/mengelola-kategori');

    }

    public function search(Request $request)
    {
        $value = $request->value;
        $data = Category::where('name', 'LIKE', "%{$value}%")->get();

        return response()->json($data);
    }

}
