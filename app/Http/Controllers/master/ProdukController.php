<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\ProdukRule_Nama;

use App\Models\tb_mst_prd as tbProduk;

class ProdukController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterKategoriId = $request->input('kategori_id');
        $filterJenisId = $request->input('jenis_id');
        $filterStatus = $request->input('status');

        $query = tbProduk::query()
            ->leftJoin('tb_mst_prd_sat as A', 'tb_mst_prd.satuan_id', '=', 'A.id')
            ->leftJoin('tb_mst_prd_kat_jns as B', 'tb_mst_prd.jenis_id', '=', 'B.id')
            ->leftJoin('tb_mst_prd_kat as C', 'B.kategori_id', '=', 'C.id')
            ->select(
                'tb_mst_prd.*',
                'C.nama as kategori_nama',
                'B.nama as jenis_nama',
                'A.nama as satuan_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_prd.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterKategoriId)) {
            $query->where('C.id', 'like', '%' . $filterKategoriId . '%');
        }

        if (!empty($filterJenisId)) {
            $query->where('B.id', 'like', '%' . $filterJenisId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_prd.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'sku', 'kategori_nama', 'jenis_nama', 'satuan_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_prd.nama',
                    'sku' => 'tb_mst_prd.sku',
                    'kategori_nama' => 'C.nama',
                    'jenis_nama' => 'B.nama',
                    'satuan_nama' => 'A.nama',
                    'status' => 'tb_mst_prd.status',
                    default => 'tb_mst_prd.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_prd.nama');
        }

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    //********************
    // show
    //********************
    public function show($id = "")
    {
        $tbProduk = new tbProduk();

        $post = $tbProduk
            ->join('tb_mst_prd_kat_jns as B', 'tb_mst_prd.jenis_id', '=', 'B.id')
            ->join('tb_mst_prd_kat as C', 'B.kategori_id', '=', 'C.id')
            ->select(
                'tb_mst_prd.*',
                'C.nama as kategori_nama',
                'C.id as kategori_id',
                'B.nama as jenis_nama'
            )
            ->where('tb_mst_prd.id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo()
    {
        $tbProduk = new tbProduk();

        $data = $tbProduk
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('urutan')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbProduk = new tbProduk();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $sku = $request->input('sku');
        $jenis_id = $request->input('jenis_id');
        $satuan_id = $request->input('satuan_id');
        $base_price = $request->input('base_price');
        $sale_price = $request->input('sale_price');
        $margin = $request->input('margin');
        $catatan = $request->input('catatan');
        $status = $request->input('status');
        $by = $request->input('by');

        // if sku = null, set rand
        if (empty($sku)) {
            $sku = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        }

        // cek error
        $errList = array(
            'nama' => ['required', new ProdukRule_Nama($id, $jenis_id, $nama)],
            'jenis_id' => 'required',
            'satuan_id' => 'required'
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'jenis_id.required' => 'Belum dipilih!',
            'satuan_id.required' => 'Belum dipilih!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            if ($id == '') {
                $post = $tbProduk
                    ->create([
                        'nama' => $nama,
                        'sku' => $sku,
                        'jenis_id' => $jenis_id,
                        'satuan_id' => $satuan_id,
                        'base_price' => $base_price,
                        'sale_price' => $sale_price,
                        'margin' => $margin,
                        'catatan' => $catatan,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbProduk
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'sku' => $sku,
                        'jenis_id' => $jenis_id,
                        'satuan_id' => $satuan_id,
                        'base_price' => $base_price,
                        'sale_price' => $sale_price,
                        'margin' => $margin,
                        'catatan' => $catatan,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan",
                "data" => [
                    "id" => $id,
                ]
            ], 200);
        }
    }


    public function gambar_add(Request $request)
    {
        // 1. Validasi file
        // $request->validate([
        //     'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // 2. Cek apakah ada file yang diupload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            // 2. Custom Nama File
            // Contoh: LOGO_NAMA-PERUSAHAAN_TIMESTAMP.png
            $slugNama = str_replace(' ', '_', strtolower($request->id));
            $extension = $file->getClientOriginalExtension(); // Ambil ekstensi asli (.jpg, .png, dll)
            $namaFileBaru = $slugNama . "_" . time() . "." . $extension;

            // 3. Simpan ke folder 'public/gambars' dengan nama baru
            $path = $file->storeAs('produk', $namaFileBaru, 'public');

            // 4. Update Database (jika perlu)
            $tbProduk = new tbProduk();

            $tbProduk->where('id', $request->id)
                ->update([
                    'gambar' => $namaFileBaru
                ]);

            return response()->json([
                'message' => 'Gambar berhasil diunggah!',
                'file_name' => $namaFileBaru,
                'path' => $path
            ]);
        }
    }

    public function gambar_show($id = null)
    {
        $tbProduk = new tbProduk();

        $produk = $tbProduk
            ->where('id', $id)
            ->first();

        if ($produk) {
            $produk->gambar = $produk->gambar ? asset('storage/produk/' . $produk->gambar) : asset('storage/empty.png');

            return response()->json($produk);
        }

        return response()->json(['message' => 'Not Found'], 404);
    }
}
