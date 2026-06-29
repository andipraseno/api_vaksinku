<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\KlinikRule_Nama;

use App\Models\tb_mst_klk as tbKlinik;

class KlinikController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterKategoriId = $request->input('kategori_id');
        $filterStatus = $request->input('status');

        $query = tbKlinik::query()
            ->leftJoin('tb_mst_klk_kat as A', 'tb_mst_klk.kategori_id', '=', 'A.id')
            ->select(
                'tb_mst_klk.*',
                'A.nama as kategori_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_klk.nama', 'like', '%' . $filterNama . '%');
            $query->orWhere('tb_mst_klk.kode', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranchId)) {
            $query->where('B.branch_id', $filterBranchId);
        }

        if (!empty($filterUnitId)) {
            $query->where('tb_mst_klk.unit_id', $filterUnitId);
        }

        if (!empty($filterKategoriId)) {
            $query->where('tb_mst_klk.kategori_id', 'like', '%' . $filterKategoriId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_klk.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kode', 'alamat', 'kategori_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_klk.nama',
                    'kode' => 'tb_mst_klk.kode',
                    'alamat' => 'tb_mst_klk.alamat',
                    'kategori_nama' => 'A.nama',
                    'status' => 'tb_mst_klk.status',
                    default => 'tb_mst_klk.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_klk.nama');
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
        $tbKlinik = new tbKlinik();

        $post = $tbKlinik
            ->leftJoin('tb_mst_pro_kot_kec as D1', 'tb_mst_klk.kecamatan_id', '=', 'D1.id')
            ->leftJoin('tb_mst_pro_kot as D2', 'D1.kota_id', '=', 'D2.id')
            ->leftJoin('tb_mst_pro as D3', 'D2.provinsi_id', '=', 'D3.id')
            ->select(
                'tb_mst_klk.*',
                'D1.nama as kecamatan_nama',
                'D1.kota_id',
                'D2.nama AS kota_nama',
                'D2.provinsi_id',
                'D3.nama AS provinsi_nama',
            )
            ->where("tb_mst_klk.id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo()
    {
        $tbKlinik = new tbKlinik();

        $data = $tbKlinik
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('urutan')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKlinik = new tbKlinik();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $alamat = $request->input('alamat');
        $handphone = $request->input('handphone');
        $email = $request->input('email');
        $pic_nama = $request->input('pic_nama');
        $pic_handphone = $request->input('pic_handphone');
        $pic_email = $request->input('pic_email');
        $pj_nama = $request->input('pj_nama');
        $pj_handphone = $request->input('pj_handphone');
        $pj_email = $request->input('pj_email');
        $nktp = $request->input('nktp');
        $npwp = $request->input('npwp');
        $otp = $request->input('otp');
        $kategori_id = $request->input('kategori_id');
        $kecamatan_id = $request->input('kecamatan_id');
        $status = $request->input('status');
        $by = $request->input('by');

        if (empty($kode)) {
            $kode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        }

        if (empty($otp)) {
            $otp = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        }

        // cek error
        $errList = array(
            'nama' => ['required', new KlinikRule_Nama($id, $nama)],
            'kategori_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kategori_id.required' => 'Belum dipilih!',
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
                $post = $tbKlinik
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
                        'email' => $email,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'pj_nama' => $pj_nama,
                        'pj_handphone' => $pj_handphone,
                        'pj_email' => $pj_email,
                        'pic_nama' => $pic_nama,
                        'pic_handphone' => $pic_handphone,
                        'pic_email' => $pic_email,
                        'otp' => $otp,
                        'kategori_id' => $kategori_id,
                        'kecamatan_id' => $kecamatan_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbKlinik
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
                        'email' => $email,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'pj_nama' => $pj_nama,
                        'pj_handphone' => $pj_handphone,
                        'pj_email' => $pj_email,
                        'pic_nama' => $pic_nama,
                        'pic_handphone' => $pic_handphone,
                        'pic_email' => $pic_email,
                        'otp' => $otp,
                        'kategori_id' => $kategori_id,
                        'kecamatan_id' => $kecamatan_id,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan",
                "data" => [
                    "id" => $id,
                ]
            ], 200);
        }
    }
}
