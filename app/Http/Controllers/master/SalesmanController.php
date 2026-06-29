<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\SalesmanRule_Nama;

use App\Models\tb_mst_slm as tbSalesman;

class SalesmanController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterGroupId = $request->input('group_id');
        $filterLevelId = $request->input('level_id');
        $filterStatus = $request->input('status');

        $query = tbSalesman::query()
            ->leftJoin('tb_mst_slm_grp as A', 'tb_mst_slm.group_id', '=', 'A.id')
            ->leftJoin('tb_mst_slm_lev as B', 'tb_mst_slm.level_id', '=', 'B.id')
            ->select(
                'tb_mst_slm.*',
                'A.nama as group_nama',
                'B.nama as level_nama'
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_slm.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterGroupId)) {
            $query->where('tb_mst_slm.group_id', $filterGroupId);
        }

        if (!empty($filterLevelId)) {
            $query->where('tb_mst_slm.level_id', $filterLevelId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_slm.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'kode', 'group_nama', 'level_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_slm.nama',
                    'kode' => 'tb_mst_slm.kode',
                    'group_nama' => 'A.nama',
                    'level_nama' => 'B.nama',
                    'status' => 'tb_mst_slm.status',
                    default => 'tb_mst_slm.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_slm.nama');
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
        $tbSalesman = new tbSalesman();

        $post = $tbSalesman
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
        $tbSalesman = new tbSalesman();

        $data = $tbSalesman
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbSalesman = new tbSalesman();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $handphone = $request->input('handphone');
        $email = $request->input('email');
        $nktp = $request->input('nktp');
        $npwp = $request->input('npwp');
        $otp = $request->input('otp');
        $alamat = $request->input('alamat');
        $group_id = $request->input('group_id');
        $level_id = $request->input('level_id');
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
            'nama' => ['required', new SalesmanRule_Nama($id, $nama)],
            'group_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'group_id.required' => 'Belum dipilih!',
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
                $post = $tbSalesman
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'handphone' => $handphone,
                        'email' => $email,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'otp' => $otp,
                        'alamat' => $alamat,
                        'group_id' => $group_id,
                        'level_id' => $level_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbSalesman
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'handphone' => $handphone,
                        'email' => $email,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'otp' => $otp,
                        'alamat' => $alamat,
                        'group_id' => $group_id,
                        'level_id' => $level_id,
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
