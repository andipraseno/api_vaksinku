<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\tb_trs_kry_srt as tbKaryawanSertifikasi;

class KaryawanSertifikasiController extends BaseController
{
    public function index($karyawan_id)
    {
        $tbKaryawanSertifikasi = new tbKaryawanSertifikasi();

        $data = $tbKaryawanSertifikasi
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
        $tbKaryawanSertifikasi = new tbKaryawanSertifikasi();

        $post = $tbKaryawanSertifikasi
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
        $tbKaryawanSertifikasi = new tbKaryawanSertifikasi();

        $id = $request->input('id');
        $karyawan_id = $request->input('karyawan_id');
        $institusi = $request->input('institusi');
        $kompetensi = $request->input('kompetensi');
        $keterangan = $request->input('keterangan');

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
                $post = $tbKaryawanSertifikasi
                    ->create([
                        'karyawan_id' => $karyawan_id,
                        'institusi' => $institusi,
                        'kompetensi' => $kompetensi,
                        'keterangan' => $keterangan,
                    ]);
            } else {
                $post = $tbKaryawanSertifikasi
                    ->where('id', $id)
                    ->update([
                        'institusi' => $institusi,
                        'kompetensi' => $kompetensi,
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
        $tbKaryawanSertifikasi = new tbKaryawanSertifikasi();

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
            $tbKaryawanSertifikasi
                ->where('id', $id)
                ->delete();

            return response()->json([
                "success" => true,
                "message" => "Data berhasil dihapus"
            ], 200);
        }
    }
}
