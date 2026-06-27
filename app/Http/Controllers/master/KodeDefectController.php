<?php

namespace App\Http\Controllers\master;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\master\KodeDefectRule_Nama;
use App\Http\Controllers\master\KodeDefectRule_Kode;

use App\Models\tb_mst_kdf as tbKodeDefect;

class KodeDefectController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterBranch = $request->input('branch_id');
        $filterUnit = $request->input('unit_id');
        $filterStatus = $request->input('status');

        $query = tbKodeDefect::query()
            ->leftJoin('tb_act_unt as A', 'tb_mst_kdf.unit_id', '=', 'A.id')
            ->leftJoin('tb_act_brc as B', 'A.branch_id', '=', 'B.id')
            ->select(
                'tb_mst_kdf.*',
                'A.nama as unit_nama',
                'B.nama as branch_nama',
            );


        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_mst_kdf.nama', 'like', '%' . $filterNama . '%');
            $query->orWhere('tb_mst_kdf.kode', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterBranch)) {
            $query->where('A.branch_id', $filterBranch);
        }

        if (!empty($filterUnit)) {
            $query->where('tb_mst_kdf.unit_id', $filterUnit);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_kdf.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'branch_id', 'unit_id', 'kode', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_kdf.nama',
                    'branch_id' => 'A.branch_id',
                    'unit_id' => 'tb_mst_kdf.unit_id',
                    'kode' => 'tb_mst_kdf.kode',
                    'status' => 'tb_mst_kdf.status',
                    default => 'tb_mst_kdf.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_kdf.nama');
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
        $tbKodeDefect = new tbKodeDefect();

        $post = $tbKodeDefect
            ->join('tb_act_unt as A', 'tb_mst_kdf.unit_id', '=', 'A.id')
            ->select(
                'tb_mst_kdf.*',
                'A.branch_id',
                'A.nama as unit_nama',
            )
            ->where('tb_mst_kdf.id', $id)
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
        $tbKodeDefect = new tbKodeDefect();

        $data = $tbKodeDefect
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
        $tbKodeDefect = new tbKodeDefect();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $unit_id = $request->input('unit_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new KodeDefectRule_Nama($id, $unit_id, $nama)],
            'kode' => ['required', new KodeDefectRule_Kode($id, $unit_id, $kode)],
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
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
                $post = $tbKodeDefect
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'unit_id' => $unit_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbKodeDefect
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'unit_id' => $unit_id,
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
