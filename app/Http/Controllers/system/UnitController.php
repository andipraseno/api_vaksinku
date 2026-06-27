<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\UnitRule_Nama;

use App\Models\tb_act_unt as tbUnit;

class UnitController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterBranchId = $request->input('branch_id');
        $filterStatus = $request->input('status');

        $query = tbUnit::query()
            ->leftJoin('tb_act_brc as A', 'tb_act_unt.branch_id', '=', 'A.id')
            ->select(
                'tb_act_unt.*',
                'A.nama as branch_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_act_unt.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranchId)) {
            $query->where('tb_act_unt.branch_id', 'like', '%' . $filterBranchId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_act_unt.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'branch_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_act_unt.nama',
                    'branch_nama' => 'A.nama',
                    'status' => 'tb_act_unt.status',
                    default => 'tb_act_unt.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_act_unt.nama');
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
        $tbUnit = new tbUnit();

        $post = $tbUnit
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($branch_id = "")
    {
        $tbUnit = new tbUnit();

        $data = $tbUnit
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1);

        $data->where('branch_id', $branch_id);

        $data = $data
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbUnit = new tbUnit();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $branch_id = $request->input('branch_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new UnitRule_Nama($id, $branch_id, $nama)],
            'branch_id' => 'required'
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'branch_id.required' => 'Belum dipilih!',
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
                $post = $tbUnit
                    ->create([
                        'nama' => $nama,
                        'branch_id' => $branch_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbUnit
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'branch_id' => $branch_id,
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
