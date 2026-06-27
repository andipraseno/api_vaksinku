<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\tb_trs_kdd as tbKandidat;

class KandidatController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterStatus = $request->input('status');

        $query = tbKandidat::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_trs_kdd.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_trs_kdd.nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_trs_kdd.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'pendidikan', 'handphone', 'posisi', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_trs_kdd.nama',
                    'pendidikan' => 'tb_trs_kdd.pendidikan',
                    'handphone' => 'tb_trs_kdd.handphone',
                    'posisi' => 'tb_trs_kdd.posisi',
                    'status' => 'tb_trs_kdd.status',
                    default => 'tb_trs_kdd.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_trs_kdd.nama');
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
        $tbKandidat = new tbKandidat();

        $post = $tbKandidat
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKandidat = new tbKandidat();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $tgl_lahir = $request->input('tgl_lahir');
        $pendidikan = $request->input('pendidikan');
        $handphone = $request->input('handphone');
        $posisi = $request->input('posisi');
        $status = $request->input('status');
        $by = $request->input('by');

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
                $post = $tbKandidat
                    ->create([
                        'nama' => $nama,
                        'jenis_kelamin' => $jenis_kelamin,
                        'tgl_lahir' => $tgl_lahir,
                        'pendidikan' => $pendidikan,
                        'handphone' => $handphone,
                        'posisi' => $posisi,
                        'status' => $status,
                        'created_by' => $by,
                    ]);
            } else {
                $post = $tbKandidat
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'jenis_kelamin' => $jenis_kelamin,
                        'tgl_lahir' => $tgl_lahir,
                        'pendidikan' => $pendidikan,
                        'handphone' => $handphone,
                        'posisi' => $posisi,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function panggil(Request $request)
    {
        $tbKandidat = new tbKandidat();

        $id = $request->input('id');
        $tgl_panggil = $request->input('tgl_panggil');
        $catatan_panggil = $request->input('catatan_panggil');

        // cek error
        $errList = array(
            'tgl_panggil' => 'required',
        );

        $errMessage = array(
            'tgl_panggil.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $post = $tbKandidat
                ->where('id', $id)
                ->update([
                    'status' => 2,
                    'tgl_panggil' => $tgl_panggil,
                    'catatan_panggil' => $catatan_panggil,
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function tes(Request $request)
    {
        $tbKandidat = new tbKandidat();

        $id = $request->input('id');
        $tgl_tes = $request->input('tgl_tes');
        $hasil_tes = $request->input('hasil_tes');
        $catatan_tes = $request->input('catatan_tes');

        // cek error
        $errList = array(
            'tgl_tes' => 'required',
        );

        $errMessage = array(
            'tgl_tes.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $post = $tbKandidat
                ->where('id', $id)
                ->update([
                    'status' => 3,
                    'tgl_tes' => $tgl_tes,
                    'hasil_tes' => $hasil_tes,
                    'catatan_tes' => $catatan_tes,
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function terima(Request $request)
    {
        $tbKandidat = new tbKandidat();

        $id = $request->input('id');
        $tgl_terima = $request->input('tgl_terima');
        $karyawan_id = $request->input('karyawan_id');
        $catatan_terima = $request->input('catatan_terima');

        // cek error
        $errList = array(
            'tgl_terima' => 'required',
        );

        $errMessage = array(
            'tgl_terima.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $post = $tbKandidat
                ->where('id', $id)
                ->update([
                    'tgl_terima' => $tgl_terima,
                    'karyawan_id' => $karyawan_id,
                    'catatan_terima' => $catatan_terima,
                    'status' => 4
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function tolak(Request $request)
    {
        $tbKandidat = new tbKandidat();

        $id = $request->input('id');
        $tgl_tolak = $request->input('tgl_tolak');
        $catatan_tolak = $request->input('catatan_tolak');

        // cek error
        $errList = array(
            'tgl_tolak' => 'required',
        );

        $errMessage = array(
            'tgl_tolak.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $post = $tbKandidat
                ->where('id', $id)
                ->update([
                    'tgl_tolak' => $tgl_tolak,
                    'catatan_tolak' => $catatan_tolak,
                    'status' => 5
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }
}
