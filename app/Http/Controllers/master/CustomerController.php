<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\CustomerRule_Nama;

use App\Models\tb_mst_cst as tbCustomer;

class CustomerController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterGroupId = $request->input('group_id');
        $filterJenisId = $request->input('jenis_id');
        $filterOrientasiId = $request->input('orientasi_id');
        $filterStatus = $request->input('status');

        $query = tbCustomer::query()
            ->leftJoin('tb_mst_cst_grp as A', 'tb_mst_cst.group_id', '=', 'A.id')
            ->leftJoin('tb_mst_cst_ort as B', 'tb_mst_cst.orientasi_id', '=', 'B.id')
            ->leftJoin('tb_mst_cst_jns as C', 'tb_mst_cst.jenis_id', '=', 'C.id')
            ->select(
                'tb_mst_cst.*',
                'A.nama as group_nama',
                'B.nama as orientasi_nama',
                'C.nama as jenis_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_cst.nama', 'like', '%' . $filterNama . '%');
            $query->orWhere('tb_mst_cst.kode', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterGroupId)) {
            $query->where('tb_mst_cst.group_id', 'like', '%' . $filterGroupId . '%');
        }

        if (!empty($filterJenisId)) {
            $query->where('tb_mst_cst.jenis_id', 'like', '%' . $filterJenisId . '%');
        }

        if (!empty($filterOrientasiId)) {
            $query->where('tb_mst_cst.orientasi_id', 'like', '%' . $filterOrientasiId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_cst.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kode', 'alamat', 'group_nama', 'orientasi_nama', 'jenis_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_cst.nama',
                    'kode' => 'tb_mst_cst.kode',
                    'alamat' => 'tb_mst_cst.alamat',
                    'group_nama' => 'A.nama',
                    'orientasi_nama' => 'B.nama',
                    'jenis_nama' => 'C.nama',
                    'status' => 'tb_mst_cst.status',
                    default => 'tb_mst_cst.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_cst.nama');
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
        $tbCustomer = new tbCustomer();

        $post = $tbCustomer
            ->where('id', $id)
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
        $tbCustomer = new tbCustomer();

        $data = $tbCustomer
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
        $tbCustomer = new tbCustomer();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $alamat = $request->input('alamat');
        $handphone = $request->input('handphone');
        $email = $request->input('email');
        $otp = $request->input('otp');
        $group_id = $request->input('group_id');
        $jenis_id = $request->input('jenis_id');
        $orientasi_id = $request->input('orientasi_id');
        $npwp = $request->input('npwp');
        $nktp = $request->input('nktp');
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
            'nama' => ['required', new CustomerRule_Nama($id, $nama)],
            'group_id' => 'required',
            'jenis_id' => 'required',
            'orientasi_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'group_id.required' => 'Belum dipilih!',
            'jenis_id.required' => 'Belum dipilih!',
            'orientasi_id.required' => 'Belum dipilih!',
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
                $post = $tbCustomer
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'group_id' => $group_id,
                        'jenis_id' => $jenis_id,
                        'orientasi_id' => $orientasi_id,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
                        'email' => $email,
                        'otp' => $otp,
                        'npwp' => $npwp,
                        'nktp' => $nktp,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbCustomer
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'group_id' => $group_id,
                        'jenis_id' => $jenis_id,
                        'orientasi_id' => $orientasi_id,
                        'alamat' => $alamat,
                        'handphone' => $handphone,
                        'email' => $email,
                        'otp' => $otp,
                        'npwp' => $npwp,
                        'nktp' => $nktp,
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
