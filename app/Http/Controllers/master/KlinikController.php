<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\KlinikRule_Nama;
use App\Http\Controllers\master\KlinikRule_Kode;

use App\Models\tb_mst_klk as tbKlinik;

class KlinikController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterKategoriId = $request->input('kategori_id');
        $filterStatus = $request->input('status');

        $query = tbKlinik::query()
            ->leftJoin('tb_mst_klk_kat as A', 'tb_mst_klk.kategori_id', '=', 'A.id')
            ->select(
                'tb_mst_klk.*',
                'A.nama as kategori_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_klk.nama', 'like', '%' . $filterNama . '%');
            $query->orWhere('tb_mst_klk.kode', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranchId)) {
            $query->where('B.branch_id', $filterBranchId);
        }

        if (!empty($filterUnitId)) {
            $query->where('tb_mst_klk.unit_id', $filterUnitId);
        }

        if (!empty($filterKategoriId)) {
            $query->where('tb_mst_klk.kategori_id', 'like', '%' . $filterKategoriId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_klk.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kode', 'kategori_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_klk.nama',
                    'kode' => 'tb_mst_klk.kode',
                    'kategori_nama' => 'A.nama',
                    'status' => 'tb_mst_klk.status',
                    default => 'tb_mst_klk.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_klk.nama');
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
        $tbKlinik = new tbKlinik();

        $post = $tbKlinik
            ->where('id', $id)
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
        $tbKlinik = new tbKlinik();

        $data = $tbKlinik
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
        $tbKlinik = new tbKlinik();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $kategori_id = $request->input('kategori_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new KlinikRule_Nama($id, $nama)],
            'kode' => ['required', new KlinikRule_Kode($id, $kode)],
            'kategori_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
            'kategori_id.required' => 'Belum dipilih!',
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
                $post = $tbKlinik
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'kategori_id' => $kategori_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbKlinik
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
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
