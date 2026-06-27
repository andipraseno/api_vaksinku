<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\tb_trs_kry as tbKaryawan;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep_bag_reg as tbRegu;
use App\Models\tb_mst_div_dep_bag_reg_sek as tbSeksi;

class KaryawanPenempatanController extends BaseController
{
    public function index($karyawan_id = "")
    {
        $tbKaryawan = new tbKaryawan();
        $tbDivisi = new tbDivisi();
        $tbDepartment = new tbDepartment();
        $tbBagian = new tbBagian();
        $tbRegu = new tbRegu();
        $tbSeksi = new tbSeksi();

        $data = $tbKaryawan
            ->leftJoin("{$tbSeksi->get_table()} AS A", "{$tbKaryawan->get_table()}.seksi_id", "=", "A.id")
            ->leftJoin("{$tbRegu->get_table()} AS B", "A.regu_id", "=", "B.id")
            ->leftJoin("{$tbBagian->get_table()} AS C", "B.bagian_id", "=", "C.id")
            ->leftJoin("{$tbDepartment->get_table()} AS D", "C.department_id", "=", "D.id")
            ->leftJoin("{$tbDivisi->get_table()} AS E", "D.divisi_id", "=", "E.id")
            ->select(
                "{$tbKaryawan->get_table()}.id",
                "{$tbKaryawan->get_table()}.nip",
                "{$tbKaryawan->get_table()}.nama",
                "{$tbKaryawan->get_table()}.seksi_id",
                "A.nama AS seksi_nama",
                "A.regu_id",
                "B.nama AS regu_nama",
                "B.bagian_id",
                "C.nama AS bagian_nama",
                "C.department_id",
                "D.nama AS department_nama",
                "D.divisi_id",
                "E.nama AS divisi_nama",
            )
            ->where("{$tbKaryawan->get_table()}.id", $karyawan_id)
            ->first();

        return response()->json([
            'data' => $data,
        ]);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKaryawan = new tbKaryawan();

        $id = $request->input('id');
        $seksi_id = $request->input('seksi_id');
        $updated_by = $request->input('updated_by');
        $updated_at = now();

        // cek error
        $errList = array(
            'divisi_id' => 'required',
            'department_id' => 'required',
            'bagian_id' => 'required',
            'regu_id' => 'required',
            'seksi_id' => 'required',
        );

        $errMessage = array(
            'divisi_id.required' => 'Tidak boleh kosong!',
            'department_id.required' => 'Tidak boleh kosong!',
            'bagian_id.required' => 'Tidak boleh kosong!',
            'regu_id.required' => 'Tidak boleh kosong!',
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
            $tbKaryawan
                ->where('id', $id)
                ->update([
                    'seksi_id' => $seksi_id,
                    'updated_by' => $updated_by,
                    'updated_at' => $updated_at,
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }
}
