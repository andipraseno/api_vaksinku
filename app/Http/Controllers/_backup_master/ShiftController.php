<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\ShiftRule_Nama;

use App\Models\tb_mst_div_dep_bag_reg_sek_shf as tbShift;
use App\Models\tb_mst_div_dep_bag_reg_sek as tbSeksi;
use App\Models\tb_mst_div_dep_bag_reg as tbRegu;
use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_trs_kry as tbKaryawan;

class ShiftController extends BaseController
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
        $filterReguId = $request->input('regu_id');
        $filterSeksiId = $request->input('seksi_id');
        $filterStatus = $request->input('status');

        $query = tbShift::query()
            ->leftJoin('tb_mst_div_dep_bag_reg_sek as A', 'tb_mst_div_dep_bag_reg_sek_shf.seksi_id', '=', 'A.id')
            ->leftJoin('tb_mst_div_dep_bag_reg as B', 'A.regu_id', '=', 'B.id')
            ->leftJoin('tb_mst_div_dep_bag as C', 'B.bagian_id', '=', 'C.id')
            ->leftJoin('tb_mst_div_dep as D', 'C.department_id', '=', 'D.id')
            ->leftJoin('tb_mst_div as E', 'D.divisi_id', '=', 'E.id')
            ->leftJoin('tb_trs_kry as F', 'tb_mst_div_dep_bag_reg_sek_shf.pic_id', '=', 'F.id')
            ->select(
                'tb_mst_div_dep_bag_reg_sek_shf.*',
                'A.nama as seksi_nama',
                'A.regu_id',
                'B.nama as regu_nama',
                'B.bagian_id',
                'C.nama as bagian_nama',
                'C.department_id',
                'D.nama as department_nama',
                'D.divisi_id',
                'E.nama as divisi_nama',
                'F.nama as pic_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_div_dep_bag_reg_sek_shf.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_div_dep_bag_reg_sek_shf.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterSeksiId)) {
            $query->where('tb_mst_div_dep_bag_reg_sek_shf.seksi_id', $filterSeksiId);
        }

        if (!empty($filterReguId)) {
            $query->where('A.regu_id', $filterReguId);
        }

        if (!empty($filterBagianId)) {
            $query->where('B.bagian_id', $filterBagianId);
        }

        if (!empty($filterDepartmentId)) {
            $query->where('C.department_id', $filterDepartmentId);
        }

        if (!empty($filterDivisiId)) {
            $query->where('D.divisi_id', $filterDivisiId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_div_dep_bag_reg_sek_shf.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama',  'divisi_nama', 'department_nama', 'bagian_nama', 'regu_nama', 'seksi_nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_div_dep_bag_reg_sek.nama',
                    'seksi_nama' => 'A.nama',
                    'regu_nama' => 'B.nama',
                    'bagian_nama' => 'C.nama',
                    'department_nama' => 'D.nama',
                    'divisi_nama' => 'E.nama',
                    'pic_nama' => 'F.nama',
                    'status' => 'tb_mst_div_dep_bag_reg_sek_shf.status',
                    default => 'tb_mst_div_dep_bag_reg_sek_shf.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_div_dep_bag_reg_sek_shf.nama');
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
        $tbShift = new tbShift();
        $tbSeksi = new tbSeksi();
        $tbRegu = new tbRegu();
        $tbBagian = new tbBagian();
        $tbDepartment = new tbDepartment();
        $tbKaryawan = new tbKaryawan();

        $post = $tbShift
            ->leftJoin("{$tbSeksi->get_table()} AS A", "{$tbShift->get_table()}.seksi_id", "=", "A.id")
            ->leftJoin("{$tbRegu->get_table()} AS B", "A.regu_id", "=", "B.id")
            ->leftJoin("{$tbBagian->get_table()} AS C", "B.bagian_id", "=", "C.id")
            ->leftJoin("{$tbDepartment->get_table()} AS D", "C.department_id", "=", "D.id")
            ->leftJoin("{$tbKaryawan->get_table()} AS E", "{$tbShift->get_table()}.pic_id", "=", "E.id")
            ->select(
                "{$tbShift->get_table()}.*",
                "A.nama AS seksi_nama",
                "A.regu_id",
                "B.nama AS regu_nama",
                "B.bagian_id",
                "C.nama AS bagian_nama",
                "C.department_id",
                "D.divisi_id",
                "E.nama AS pic_nama"
            )
            ->where("{$tbShift->get_table()}.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($seksi_id = "")
    {
        $tbShift = new tbShift();

        $data = $tbShift
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('seksi_id', $seksi_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbShift = new tbShift();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $seksi_id = $request->input('seksi_id');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new SeksiRule_Nama($id, $seksi_id, $nama)],
            'seksi_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'seksi_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbShift
                    ->create([
                        'nama' => $nama,
                        'seksi_id' => $seksi_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbShift
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'seksi_id' => $seksi_id,
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
