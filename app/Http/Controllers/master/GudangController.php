<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\GudangRule_Nama;

use App\Models\tb_mst_gdg as tbGudang;

class GudangController extends BaseController
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
        $filterGroupId = $request->input('group_id');
        $filterStatus = $request->input('status');

        $query = tbGudang::query()
            ->leftJoin('tb_mst_gdg_grp as A', 'tb_mst_gdg.group_id', '=', 'A.id')
            ->leftJoin('tb_act_unt as B', 'tb_mst_gdg.unit_id', '=', 'B.id')
            ->select(
                'tb_mst_gdg.*',
                'A.nama as group_nama',
                'B.nama as unit_nama'
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_gdg.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranchId)) {
            $query->where('B.branch_id', $filterBranchId);
        }

        if (!empty($filterUnitId)) {
            $query->where('tb_mst_gdg.unit_id', $filterUnitId);
        }

        if (!empty($filterGroupId)) {
            $query->where('tb_mst_gdg.group_id', $filterGroupId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_gdg.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'unit_nama', 'group_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_gdg.nama',
                    'unit_nama' => 'B.nama',
                    'group_nama' => 'A.nama',
                    'status' => 'tb_mst_gdg.status',
                    default => 'tb_mst_gdg.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_gdg.nama');
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
        $tbGudang = new tbGudang();

        $post = $tbGudang
            ->join('tb_act_unt as A', 'tb_mst_gdg.unit_id', '=', 'A.id')
            ->select(
                'tb_mst_gdg.*',
                'A.branch_id',
                'A.nama as unit_nama'
            )
            ->where('tb_mst_gdg.id', $id)
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
        $tbGudang = new tbGudang();

        $data = $tbGudang
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
        $tbGudang = new tbGudang();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $urutan = $request->input('urutan');
        $unit_id = $request->input('unit_id');
        $group_id = $request->input('group_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new GudangRule_Nama($id, $unit_id, $nama)],
            'group_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'group_id.required' => 'Belum dipilih!',
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
                $post = $tbGudang
                    ->create([
                        'nama' => $nama,
                        'urutan' => $urutan,
                        'unit_id' => $unit_id,
                        'group_id' => $group_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbGudang
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'urutan' => $urutan,
                        'unit_id' => $unit_id,
                        'group_id' => $group_id,
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
