<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\DepartmentRule_Nama;

use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_trs_kry as tbKaryawan;

class DepartmentController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterDivisiId = $request->input('divisi_id');
        $filterStatus = $request->input('status');

        $query = tbDepartment::query()
            ->leftJoin('tb_mst_div as A', 'tb_mst_div_dep.divisi_id', '=', 'A.id')
            ->leftJoin('tb_trs_kry as B', 'tb_mst_div_dep.pic_id', '=', 'B.id')
            ->select(
                'tb_mst_div_dep.*',
                'A.nama as divisi_nama',
                'B.nama as pic_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_div_dep.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_div_dep.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterDivisiId)) {
            $query->where('tb_mst_div_dep.divisi_id', $filterDivisiId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_div_dep.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'divisi_nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_div_dep.nama',
                    'divisi_nama' => 'A.nama',
                    'pic_nama' => 'B.nama',
                    'status' => 'tb_mst_div_dep.status',
                    default => 'tb_mst_div_dep.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_div_dep.nama');
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
        $tbDepartment = new tbDepartment();
        $tbKaryawan = new tbKaryawan();

        $post = $tbDepartment
            ->leftJoin("{$tbKaryawan->get_table()} AS A", "{$tbDepartment->get_table()}.pic_id", "=", "A.id")
            ->select(
                "{$tbDepartment->get_table()}.*",
                "A.nama AS pic_nama"
            )
            ->where("{$tbDepartment->get_table()}.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($divisi_id = "")
    {
        $tbDepartment = new tbDepartment();

        $data = $tbDepartment
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('divisi_id', $divisi_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbDepartment = new tbDepartment();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $divisi_id = $request->input('divisi_id');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new DepartmentRule_Nama($id, $divisi_id, $nama)],
            'divisi_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'divisi_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbDepartment
                    ->create([
                        'nama' => $nama,
                        'divisi_id' => $divisi_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $post = $tbDepartment
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'divisi_id' => $divisi_id,
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
