<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\KategoriBarangRule_Nama;

use App\Models\tb_mst_brg_kat as tbKategoriBarang;

class KategoriBarangController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterStatus = $request->input('status');

        $query = tbKategoriBarang::query()
            ->leftJoin('tb_trs_kry as A', 'tb_mst_brg_kat.pic_id', '=', 'A.id')
            ->select(
                'tb_mst_brg_kat.*',
                'A.nama as pic_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_brg_kat.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_brg_kat.nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_brg_kat.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_brg_kat.nama',
                    'pic_nama' => 'A.nama',
                    'status' => 'tb_mst_brg_kat.status',
                    default => 'tb_mst_brg_kat.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_brg_kat.nama');
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
        $tbKategoriBarang = new tbKategoriBarang();
        $tbKaryawan = new tbKaryawan();

        $post = $tbKategoriBarang
            ->leftJoin("{$tbKaryawan->get_table()} AS A", "{$tbKategoriBarang->get_table()}.pic_id", "=", "A.id")
            ->select(
                "{$tbKategoriBarang->get_table()}.*",
                "A.nama AS pic_nama"
            )
            ->where("{$tbKategoriBarang->get_table()}.id", $id)
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
        $tbKategoriBarang = new tbKategoriBarang();

        $data = $tbKategoriBarang
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKategoriBarang = new tbKategoriBarang();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new KategoriBarangRule_Nama($id, $nama)],
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
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
                $post = $tbKategoriBarang
                    ->create([
                        'nama' => $nama,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbKategoriBarang
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'pic_id' => $pic_id,
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
