<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\tb_trs_kry as tbKaryawan;

class KaryawanJatahLiburController extends BaseController
{
    public function index($karyawan_id = "")
    {
        $tbKaryawan = new tbKaryawan();

        $data = $tbKaryawan
            ->where("id", $karyawan_id)
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
        $jatah_cuti = $request->input('jatah_cuti');
        $jatah_imtp = $request->input('jatah_imtp');

        $tbKaryawan
            ->where('id', $id)
            ->update([
                'jatah_cuti' => $jatah_cuti,
                'jatah_imtp' => $jatah_imtp,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan"
        ], 200);
    }
}
