<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\MesinRule_Nama;
use App\Http\Controllers\master\MesinRule_Kode;

use App\Models\tb_mst_msn as tbMesin;

class MesinController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterBranchId = $request->input('branch_id');
        $filterUnitId = $request->input('unit_id');
        $filterKategoriId = $request->input('kategori_id');
        $filterStatus = $request->input('status');

        $query = tbMesin::query()
            ->leftJoin('tb_mst_msn_kat as A', 'tb_mst_msn.kategori_id', '=', 'A.id')
            ->leftJoin('tb_act_unt as B', 'tb_mst_msn.unit_id', '=', 'B.id')
            ->select(
                'tb_mst_msn.*',
                'A.nama as kategori_nama',
                'B.nama as unit_nama',
                'B.branch_id'
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_msn.nama', 'like', '%' . $filterNama . '%');
            $query->orWhere('tb_mst_msn.kode', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranchId)) {
            $query->where('B.branch_id', $filterBranchId);
        }

        if (!empty($filterUnitId)) {
            $query->where('tb_mst_msn.unit_id', $filterUnitId);
        }

        if (!empty($filterKategoriId)) {
            $query->where('tb_mst_msn.kategori_id', 'like', '%' . $filterKategoriId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_msn.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kode', 'unit_nama', 'kategori_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_msn.nama',
                    'kode' => 'tb_mst_msn.kode',
                    'unit_nama' => 'B.nama',
                    'kategori_nama' => 'A.nama',
                    'status' => 'tb_mst_msn.status',
                    default => 'tb_mst_msn.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_msn.nama');
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
        $tbMesin = new tbMesin();

        $post = $tbMesin
            ->join('tb_act_unt as A', 'tb_mst_msn.unit_id', '=', 'A.id')
            ->select(
                'tb_mst_msn.*',
                'A.branch_id',
                'A.nama as unit_nama'
            )
            ->where('tb_mst_msn.id', $id)
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
        $tbMesin = new tbMesin();

        $data = $tbMesin
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
        $tbMesin = new tbMesin();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $unit_id = $request->input('unit_id');
        $kategori_id = $request->input('kategori_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new MesinRule_Nama($id, $unit_id, $nama)],
            'kode' => ['required', new MesinRule_Kode($id, $unit_id, $kode)],
            'kategori_id' => 'required',
            'unit_id' => 'required'
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
            'kategori_id.required' => 'Belum dipilih!',
            'unit_id.required' => 'Belum dipilih!',
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
                $post = $tbMesin
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'kategori_id' => $kategori_id,
                        'unit_id' => $unit_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbMesin
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'kategori_id' => $kategori_id,
                        'unit_id' => $unit_id,
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
