<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\tb_trs_kry_pkj as tbKaryawanPengalaman;

class KaryawanPengalamanController extends BaseController
{
    public function index($karyawan_id)
    {
        $tbKaryawanPengalaman = new tbKaryawanPengalaman();

        $data = $tbKaryawanPengalaman
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
        $tbKaryawanPengalaman = new tbKaryawanPengalaman();

        $post = $tbKaryawanPengalaman
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
        $tbKaryawanPengalaman = new tbKaryawanPengalaman();

        $id = $request->input('id');
        $karyawan_id = $request->input('karyawan_id');
        $perusahaan = $request->input('perusahaan');
        $jabatan = $request->input('jabatan');
        $tgl_masuk = $request->input('tgl_masuk');
        $tgl_keluar = $request->input('tgl_keluar');
        $keterangan = $request->input('keterangan');

        // cek error
        $errList = array(
            'perusahaan' => 'required',
        );

        $errMessage = array(
            'perusahaan.required' => 'Tidak boleh kosong!',
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
                $post = $tbKaryawanPengalaman
                    ->create([
                        'karyawan_id' => $karyawan_id,
                        'perusahaan' => $perusahaan,
                        'jabatan' => $jabatan,
                        'tgl_masuk' => $tgl_masuk,
                        'tgl_keluar' => $tgl_keluar,
                        'keterangan' => $keterangan,
                    ]);
            } else {
                $post = $tbKaryawanPengalaman
                    ->where('id', $id)
                    ->update([
                        'perusahaan' => $perusahaan,
                        'jabatan' => $jabatan,
                        'tgl_masuk' => $tgl_masuk,
                        'tgl_keluar' => $tgl_keluar,
                        'keterangan' => $keterangan,
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
        $tbKaryawanPengalaman = new tbKaryawanPengalaman();

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
            $tbKaryawanPengalaman
                ->where('id', $id)
                ->delete();

            return response()->json([
                "success" => true,
                "message" => "Data berhasil dihapus"
            ], 200);
        }
    }
}
