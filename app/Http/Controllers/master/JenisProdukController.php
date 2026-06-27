<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\JenisProdukRule_Nama;

use App\Models\tb_mst_prd_kat_jns as tbJenisProduk;

class JenisProdukController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterKategoriId = $request->input('kategori_id');
        $filterStatus = $request->input('status');

        $query = tbJenisProduk::query()
            ->leftJoin('tb_mst_prd_kat as A', 'tb_mst_prd_kat_jns.kategori_id', '=', 'A.id')
            ->select(
                'tb_mst_prd_kat_jns.*',
                'A.nama as kategori_nama'
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_prd_kat_jns.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_prd_kat_jns.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterKategoriId)) {
            $query->where('tb_mst_prd_kat_jns.kategori_id', $filterKategoriId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_prd_kat_jns.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kategori_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_prd_kat_jns.nama',
                    'kategori_nama' => 'A.nama',
                    'status' => 'tb_mst_prd_kat_jns.status',
                    default => 'tb_mst_prd_kat_jns.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_prd_kat_jns.nama');
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
        $tbJenisProduk = new tbJenisProduk();

        $post = $tbJenisProduk
            ->where("id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($kategori_id = "")
    {
        $tbJenisProduk = new tbJenisProduk();

        $data = $tbJenisProduk
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('kategori_id', $kategori_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbJenisProduk = new tbJenisProduk();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kategori_id = $request->input('kategori_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new JenisProdukRule_Nama($id, $kategori_id, $nama)],
            'kategori_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kategori_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbJenisProduk
                    ->create([
                        'nama' => $nama,
                        'kategori_id' => $kategori_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $post = $tbJenisProduk
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kategori_id' => $kategori_id,
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
}
