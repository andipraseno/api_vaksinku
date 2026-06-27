<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\tb_trs_kry_klg as tbKaryawanKeluarga;

class KaryawanKeluargaController extends BaseController
{
    public function index($karyawan_id)
    {
        $tbKaryawanKeluarga = new tbKaryawanKeluarga();

        $data = $tbKaryawanKeluarga
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
        $tbKaryawanKeluarga = new tbKaryawanKeluarga();

        $post = $tbKaryawanKeluarga
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
        $tbKaryawanKeluarga = new tbKaryawanKeluarga();

        $id = $request->input('id');
        $karyawan_id = $request->input('karyawan_id');
        $nama = $request->input('nama');
        $tgl_lahir = $request->input('tgl_lahir');
        $hubungan = $request->input('hubungan');
        $tanggungan = $request->input('tanggungan');
        $tinggal_serumah = $request->input('tinggal_serumah');
        $alamat = $request->input('alamat');
        $handphone = $request->input('handphone');

        // cek error
        $errList = array(
            'nama' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
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
                $post = $tbKaryawanKeluarga
                    ->create([
                        'karyawan_id' => $karyawan_id,
                        'nama' => $nama,
                        'tgl_lahir' => $tgl_lahir,
                        'hubungan' => $hubungan,
                        'tanggungan' => $tanggungan,
                        'tinggal_serumah' => $tinggal_serumah,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
                    ]);
            } else {
                $post = $tbKaryawanKeluarga
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'tgl_lahir' => $tgl_lahir,
                        'hubungan' => $hubungan,
                        'tanggungan' => $tanggungan,
                        'tinggal_serumah' => $tinggal_serumah,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
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
        $tbKaryawanKeluarga = new tbKaryawanKeluarga();

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
            $tbKaryawanKeluarga
                ->where('id', $id)
                ->delete();

            return response()->json([
                "success" => true,
                "message" => "Data berhasil dihapus"
            ], 200);
        }
    }
}
