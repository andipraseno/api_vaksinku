<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\BranchRule_Nama;
use App\Http\Controllers\system\BranchRule_Kode;

use App\Models\tb_act_brc as tbBranch;

class BranchController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        $filterNama = $request->input('nama');
        $filterStatus = $request->input('status');

        $query = tbBranch::query();

        $recordsTotal = tbBranch::count();

        // Pencarian global
        if (!empty($searchValue)) {
            $query->where('nama', 'like', '%' . $searchValue . '%');
        }

        // Filter nama dan kode
        if (!empty($filterNama)) {
            $query->where(function ($q) use ($filterNama) {
                $q->where('nama', 'like', '%' . $filterNama . '%')
                    ->orWhere('kode', 'like', '%' . $filterNama . '%');
            });
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['id', 'nama', 'kode', 'status'])) {
                $field = match ($orderColumnName) {
                    'id', 'nama', 'kode', 'status' => 'tb_act_brc.' . $orderColumnName,
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            // Default order
            $query->orderBy('nama');
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
    public function show($id)
    {
        $tbBranch = new tbBranch();

        $post = $tbBranch
            ->where("id", $id)
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
        $tbBranch = new tbBranch();

        $data = $tbBranch
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbBranch = new tbBranch();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new BranchRule_Nama($id, $nama)],
            'kode' => ['required', new BranchRule_Kode($id, $kode)],
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
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
                $post = $tbBranch
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'status' => $status,
                        'created_by' => $by,
                    ]);
            } else {
                $post = $tbBranch
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }
}
