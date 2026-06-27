<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\tb_trs_kry_pdd as tbKaryawanPendidikan;

class KaryawanPendidikanController extends BaseController
{
    public function index($karyawan_id)
    {
        $tbKaryawanPendidikan = new tbKaryawanPendidikan();

        $data = $tbKaryawanPendidikan
            ->where('karyawan_id', $karyawan_id)
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    //********************
    // show
    //********************
    public function show($id = "")
    {
        $tbKaryawanPendidikan = new tbKaryawanPendidikan();

        $post = $tbKaryawanPendidikan
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            "data" => $post,
        ]);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKaryawanPendidikan = new tbKaryawanPendidikan();

        $id = $request->input('id');
        $karyawan_id = $request->input('karyawan_id');
        $tingkat_pendidikan_id = $request->input('tingkat_pendidikan_id');
        $tingkat_pendidikan_nama = $request->input('tingkat_pendidikan_nama');
        $institusi = $request->input('institusi');
        $jurusan = $request->input('jurusan');
        $nilai = $request->input('nilai');
        $lulus = $request->input('lulus');
        $tahun_lulus = $request->input('tahun_lulus');

        // cek error
        $errList = array(
            'institusi' => 'required',
        );

        $errMessage = array(
            'institusi.required' => 'Tidak boleh kosong!',
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
                $post = $tbKaryawanPendidikan
                    ->create([
                        'karyawan_id' => $karyawan_id,
                        'tingkat_pendidikan_id' => $tingkat_pendidikan_id,
                        'tingkat_pendidikan_nama' => $tingkat_pendidikan_nama,
                        'institusi' => $institusi,
                        'jurusan' => $jurusan,
                        'nilai' => $nilai,
                        'lulus' => $lulus,
                        'tahun_lulus' => $tahun_lulus,
                    ]);
            } else {
                $post = $tbKaryawanPendidikan
                    ->where('id', $id)
                    ->update([
                        'tingkat_pendidikan_id' => $tingkat_pendidikan_id,
                        'tingkat_pendidikan_nama' => $tingkat_pendidikan_nama,
                        'institusi' => $institusi,
                        'jurusan' => $jurusan,
                        'nilai' => $nilai,
                        'lulus' => $lulus,
                        'tahun_lulus' => $tahun_lulus,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function delete($id)
    {
        $tbKaryawanPendidikan = new tbKaryawanPendidikan();

        // cek error
        $errList = array(
            'id' => 'required',
        );

        $errMessage = array(
            'id.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $tbKaryawanPendidikan
                ->where('id', $id)
                ->delete();

            return response()->json([
                "success" => true,
                "message" => "Data berhasil dihapus"
            ], 200);
        }
    }
}
