<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\GroupCustomerRule_Nama;

use App\Models\tb_mst_cst_grp as tbGroupCustomer;

class GroupCustomerController extends BaseController
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

        $query = tbGroupCustomer::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_cst_grp.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_cst_grp.nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_cst_grp.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_cst_grp.nama',
                    'status' => 'tb_mst_cst_grp.status',
                    default => 'tb_mst_cst_grp.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_cst_grp.nama');
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
        $tbGroupCustomer = new tbGroupCustomer();

        $post = $tbGroupCustomer
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
        $tbGroupCustomer = new tbGroupCustomer();

        $data = $tbGroupCustomer
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
        $tbGroupCustomer = new tbGroupCustomer();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new GroupCustomerRule_Nama($id, $nama)],
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
                $post = $tbGroupCustomer
                    ->create([
                        'nama' => $nama,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbGroupCustomer
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
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
