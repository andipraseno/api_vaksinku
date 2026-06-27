<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\BagianRule_Nama;

use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_trs_kry as tbKaryawan;

class BagianController extends BaseController
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
        $filterDepartmentId = $request->input('department_id');
        $filterStatus = $request->input('status');

        $query = tbBagian::query()
            ->leftJoin('tb_mst_div_dep as A', 'tb_mst_div_dep_bag.department_id', '=', 'A.id')
            ->leftJoin('tb_mst_div as B', 'A.divisi_id', '=', 'B.id')
            ->leftJoin('tb_trs_kry as C', 'tb_mst_div_dep_bag.pic_id', '=', 'C.id')
            ->select(
                'tb_mst_div_dep_bag.*',
                'A.nama as department_nama',
                'B.nama as divisi_nama',
                'C.nama as pic_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_div_dep_bag.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_div_dep_bag.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterDepartmentId)) {
            $query->where('tb_mst_div_dep_bag.department_id', $filterDepartmentId);
        }

        if (!empty($filterDivisiId)) {
            $query->where('A.divisi_id', $filterDivisiId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_div_dep_bag.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'divisi_nama', 'department_nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_div_dep_bag.nama',
                    'department_nama' => 'A.nama',
                    'divisi_nama' => 'B.nama',
                    'pic_nama' => 'C.nama',
                    'status' => 'tb_mst_div_dep_bag.status',
                    default => 'tb_mst_div_dep_bag.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_div_dep_bag.nama');
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
        $tbBagian = new tbBagian();
        $tbDepartment = new tbDepartment();
        $tbKaryawan = new tbKaryawan();

        $post = $tbBagian
            ->leftJoin("{$tbDepartment->get_table()} AS A", "{$tbBagian->get_table()}.department_id", "=", "A.id")
            ->leftJoin("{$tbKaryawan->get_table()} AS B", "{$tbBagian->get_table()}.pic_id", "=", "B.id")
            ->select(
                "{$tbBagian->get_table()}.*",
                "A.nama AS department_nama",
                "A.divisi_id",
                "B.nama AS pic_nama"
            )
            ->where("{$tbBagian->get_table()}.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($department_id = "")
    {
        $tbBagian = new tbBagian();

        $data = $tbBagian
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('department_id', $department_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbBagian = new tbBagian();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $department_id = $request->input('department_id');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new BagianRule_Nama($id, $department_id, $nama)],
            'department_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'department_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbBagian
                    ->create([
                        'nama' => $nama,
                        'department_id' => $department_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbBagian
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'department_id' => $department_id,
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
