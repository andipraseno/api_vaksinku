<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\ReguRule_Nama;

use App\Models\tb_mst_div_dep_bag_reg as tbRegu;
use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_trs_kry as tbKaryawan;

class ReguController extends BaseController
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
        $filterBagianId = $request->input('bagian_id');
        $filterStatus = $request->input('status');

        $query = tbRegu::query()
            ->leftJoin('tb_mst_div_dep_bag as A', 'tb_mst_div_dep_bag_reg.bagian_id', '=', 'A.id')
            ->leftJoin('tb_mst_div_dep as B', 'A.department_id', '=', 'B.id')
            ->leftJoin('tb_mst_div as C', 'B.divisi_id', '=', 'C.id')
            ->leftJoin('tb_trs_kry as D', 'tb_mst_div_dep_bag_reg.pic_id', '=', 'D.id')
            ->select(
                'tb_mst_div_dep_bag_reg.*',
                'A.nama as bagian_nama',
                'B.nama as department_nama',
                'C.nama as divisi_nama',
                'D.nama as pic_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_div_dep_bag_reg.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_div_dep_bag_reg.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBagianId)) {
            $query->where('tb_mst_div_dep_bag_reg.bagian_id', $filterBagianId);
        }

        if (!empty($filterDepartmentId)) {
            $query->where('B.department_id', $filterDepartmentId);
        }

        if (!empty($filterDivisiId)) {
            $query->where('A.divisi_id', $filterDivisiId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_div_dep_bag_reg.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'divisi_nama', 'department_nama', 'bagian_nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_div_dep_bag_reg.nama',
                    'bagian_nama' => 'A.nama',
                    'department_nama' => 'B.nama',
                    'divisi_nama' => 'C.nama',
                    'pic_nama' => 'D.nama',
                    'status' => 'tb_mst_div_dep_bag_reg.status',
                    default => 'tb_mst_div_dep_bag_reg.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_div_dep_bag_reg.nama');
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
        $tbRegu = new tbRegu();
        $tbBagian = new tbBagian();
        $tbDepartment = new tbDepartment();
        $tbKaryawan = new tbKaryawan();

        $post = $tbRegu
            ->leftJoin("{$tbBagian->get_table()} AS A", "{$tbRegu->get_table()}.bagian_id", "=", "A.id")
            ->leftJoin("{$tbDepartment->get_table()} AS B", "A.department_id", "=", "B.id")
            ->leftJoin("{$tbKaryawan->get_table()} AS C", "{$tbRegu->get_table()}.pic_id", "=", "C.id")
            ->select(
                "{$tbRegu->get_table()}.*",
                "A.nama AS bagian_nama",
                "A.department_id",
                "B.nama AS department_nama",
                "B.divisi_id",
                "C.nama AS pic_nama"
            )
            ->where("{$tbRegu->get_table()}.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($bagian_id = "")
    {
        $tbRegu = new tbRegu();

        $data = $tbRegu
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('bagian_id', $bagian_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbRegu = new tbRegu();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $bagian_id = $request->input('bagian_id');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new ReguRule_Nama($id, $bagian_id, $nama)],
            'bagian_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'bagian_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbRegu
                    ->create([
                        'nama' => $nama,
                        'bagian_id' => $bagian_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbRegu
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'bagian_id' => $bagian_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan",
                "data" => [
                    "id" => $id
                ]
            ], 200);
        }
    }
}
