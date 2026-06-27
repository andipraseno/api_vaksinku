<?php

namespace App\Http\Controllers\produksi;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\produksi\SubProsesRule_Nama;

use App\Models\tb_trs_prc_sub as tbSubProses;
use App\Models\tb_trs_prc as tbProses;

class SubProsesController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterProsesId = $request->input('proses_id');
        $filterStatus = $request->input('status');

        $query = tbSubProses::query()
            ->leftJoin('tb_trs_prc as A', 'tb_trs_prc_sub.proses_id', '=', 'A.id')
            ->select(
                'tb_trs_prc_sub.*',
                'A.nama as proses_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_trs_prc_sub.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterProsesId)) {
            $query->where('tb_trs_prc_sub.proses_id', 'like', '%' . $filterProsesId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_trs_prc_sub.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'proses_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_trs_prc_sub.nama',
                    'proses_nama' => 'A.nama',
                    'status' => 'tb_trs_prc_sub.status',
                    default => 'tb_trs_prc_sub.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_trs_prc_sub.nama');
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
        $tbSubProses = new tbSubProses();

        $post = $tbSubProses
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($filterProsesId  = null)
    {
        $tbSubProses = new tbSubProses();

        $query = $tbSubProses
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1);

        if (!empty($filterProsesId)) {
            $query->where('proses_id', $filterProsesId);
        }

        $data = $query
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbSubProses = new tbSubProses();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $proses_id = $request->input('proses_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new SubProsesRule_Nama($id, $proses_id, $nama)],
            'proses_id' => 'required'
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'proses_id.required' => 'Belum dipilih!',
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
                $post = $tbSubProses
                    ->create([
                        'nama' => $nama,
                        'proses_id' => $proses_id,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbSubProses
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'proses_id' => $proses_id,
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
