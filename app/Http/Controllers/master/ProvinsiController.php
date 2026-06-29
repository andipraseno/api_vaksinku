<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\tb_mst_pro as tbProvinsi;
use App\Models\tb_mst_pro_kot as tbKota;
use App\Models\tb_mst_pro_kot_kec as tbKecamatan;
use App\Models\tb_mst_pro_kot_kec_kel as tbKelurahan;

class ProvinsiController extends BaseController
{
    public function provinsi()
    {
        $tbProvinsi = new tbProvinsi();

        $data = $tbProvinsi
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function kota($provinsi_id = null)
    {
        $tbKota = new tbKota();

        $data = $tbKota
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('provinsi_id', $provinsi_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function kecamatan($kota_id = null)
    {
        $tbKecamatan = new tbKecamatan();

        $data = $tbKecamatan
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->where('kota_id', $kota_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function kelurahan($kecamatan_id = null)
    {
        $tbKelurahan = new tbKelurahan();

        $data = $tbKelurahan
            ->select(
                'id',
                'nama',
            )
            ->where('status', 1)
            ->where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function kelurahan_selected($id = null)
    {
        $tbKelurahan = new tbKelurahan();

        $data = $tbKelurahan
            ->select(
                'id',
                'nama',
                'kode_pos',
            )
            ->where('status', 1)
            ->where('id', $id)
            ->first();

        return response()->json($data);
    }
}
