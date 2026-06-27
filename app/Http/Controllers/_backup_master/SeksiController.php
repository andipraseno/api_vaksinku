<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\SeksiRule_Nama;

use App\Models\tb_mst_div_dep_bag_reg_sek as tbSeksi;
use App\Models\tb_mst_div_dep_bag_reg as tbRegu;
use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_trs_kry as tbKaryawan;

class SeksiController extends BaseController
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
        $filterStatus = $request->input('status');

        $query = tbSeksi::query()
            ->leftJoin('tb_mst_div_dep_bag_reg as A', 'tb_mst_div_dep_bag_reg_sek.regu_id', '=', 'A.id')
            ->leftJoin('tb_mst_div_dep_bag as B', 'A.bagian_id', '=', 'B.id')
            ->leftJoin('tb_mst_div_dep as C', 'B.department_id', '=', 'C.id')
            ->leftJoin('tb_mst_div as D', 'C.divisi_id', '=', 'D.id')
            ->leftJoin('tb_trs_kry as E', 'tb_mst_div_dep_bag_reg_sek.pic_id', '=', 'E.id')
            ->select(
                'tb_mst_div_dep_bag_reg_sek.*',
                'A.nama as regu_nama',
                'B.nama as bagian_nama',
                'C.nama as department_nama',
                'D.nama as divisi_nama',
                'E.nama as pic_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_div_dep_bag_reg_sek.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_div_dep_bag_reg_sek.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterReguId)) {
            $query->where('tb_mst_div_dep_bag_reg_sek.regu_id', $filterReguId);
        }

        if (!empty($filterBagianId)) {
            $query->where('A.bagian_id', $filterBagianId);
        }

        if (!empty($filterDepartmentId)) {
            $query->where('C.department_id', $filterDepartmentId);
        }

        if (!empty($filterDivisiId)) {
            $query->where('B.divisi_id', $filterDivisiId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_div_dep_bag_reg_sek.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama',  'divisi_nama', 'department_nama', 'bagian_nama', 'regu_nama', 'pic_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_div_dep_bag_reg_sek.nama',
                    'regu_nama' => 'A.nama',
                    'bagian_nama' => 'B.nama',
                    'department_nama' => 'C.nama',
                    'divisi_nama' => 'D.nama',
                    'pic_nama' => 'E.nama',
                    'status' => 'tb_mst_div_dep_bag_reg_sek.status',
                    default => 'tb_mst_div_dep_bag_reg_sek.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_div_dep_bag_reg_sek.nama');
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
        $tbSeksi = new tbSeksi();
        $tbRegu = new tbRegu();
        $tbBagian = new tbBagian();
        $tbDepartment = new tbDepartment();
        $tbKaryawan = new tbKaryawan();

        $post = $tbSeksi
            ->leftJoin("{$tbRegu->get_table()} AS A", "{$tbSeksi->get_table()}.regu_id", "=", "A.id")
            ->leftJoin("{$tbBagian->get_table()} AS B", "A.bagian_id", "=", "B.id")
            ->leftJoin("{$tbDepartment->get_table()} AS C", "B.department_id", "=", "C.id")
            ->leftJoin("{$tbKaryawan->get_table()} AS D", "{$tbSeksi->get_table()}.pic_id", "=", "D.id")
            ->select(
                "{$tbSeksi->get_table()}.*",
                "A.nama AS regu_nama",
                "A.bagian_id",
                "B.nama AS bagian_nama",
                "B.department_id",
                "C.nama AS department_nama",
                "C.divisi_id",
                "D.nama AS pic_nama"
            )
            ->where("{$tbSeksi->get_table()}.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($regu_id = "")
    {
        $tbSeksi = new tbSeksi();

        $data = $tbSeksi
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('regu_id', $regu_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbSeksi = new tbSeksi();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $regu_id = $request->input('regu_id');
        $pic_id = $request->input('pic_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new SeksiRule_Nama($id, $regu_id, $nama)],
            'regu_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'regu_id.required' => 'Tidak boleh kosong!',
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
                $post = $tbSeksi
                    ->create([
                        'nama' => $nama,
                        'regu_id' => $regu_id,
                        'pic_id' => $pic_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbSeksi
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'regu_id' => $regu_id,
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
