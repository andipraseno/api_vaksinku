<?php

namespace App\Http\Controllers\produksi;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\produksi\StepProsesRule_Nama;

use App\Models\tb_trs_prc_sub_stp as tbStepProses;
use App\Models\tb_trs_prc_sub as tbSubProses;
use App\Models\tb_trs_prc as tbProses;

class StepProsesController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterProsesId = $request->input('proses_id');
        $filterSubProsesId = $request->input('sub_proses_id');
        $filterStatus = $request->input('status');

        $query = tbStepProses::query()
            ->leftJoin('tb_trs_prc_sub as A', 'tb_trs_prc_sub_stp.sub_proses_id', '=', 'A.id')
            ->leftJoin('tb_trs_prc as B', 'A.proses_id', '=', 'B.id')
            ->select(
                'tb_trs_prc_sub_stp.*',
                'A.nama as sub_proses_nama',
                'A.proses_id',
                'B.nama as proses_nama'
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_trs_prc_sub_stp.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterSubProsesId)) {
            $query->where('tb_trs_prc_sub_stp.sub_proses_id', 'like', '%' . $filterSubProsesId . '%');
        }

        if (!empty($filterProsesId)) {
            $query->where('A.proses_id', 'like', '%' . $filterProsesId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_trs_prc_sub_stp.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'urutan', 'proses_nama',  'sub_proses_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_trs_prc_sub_stp.nama',
                    'urutan' => 'tb_trs_prc_sub_stp.urutan',
                    'proses_nama' => 'B.nama',
                    'sub_proses_nama' => 'A.nama',
                    'status' => 'tb_trs_prc_sub_stp.status',
                    default => 'tb_trs_prc_sub_stp.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_trs_prc_sub_stp.nama');
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
        $tbStepProses = new tbStepProses();

        $post = $tbStepProses
            ->join('tb_trs_prc_sub as A', 'tb_trs_prc_sub_stp.sub_proses_id', '=', 'A.id')
            ->join('tb_trs_prc as B', 'A.proses_id', '=', 'B.id')
            ->select(
                'tb_trs_prc_sub_stp.*',
                'A.nama as sub_proses_nama',
                'A.proses_id',
                'B.nama as proses_nama'
            )
            ->where('tb_trs_prc_sub_stp.id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo($filterSubProsesId = null)
    {
        $tbStepProses = new tbStepProses();

        $query = $tbStepProses
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1);

        if (!empty($filterSubProsesId)) {
            $query->where('sub_proses_id', $filterSubProsesId);
        }

        $data = $query
            ->orderBy('urutan')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbStepProses = new tbStepProses();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $urutan = $request->input('urutan');
        $sub_proses_id = $request->input('sub_proses_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new StepProsesRule_Nama($id, $sub_proses_id, $nama)],
            'proses_id' => 'required',
            'sub_proses_id' => 'required'
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'proses_id.required' => 'Belum dipilih!',
            'sub_proses_id.required' => 'Belum dipilih!',
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
                $post = $tbStepProses
                    ->create([
                        'nama' => $nama,
                        'sub_proses_id' => $sub_proses_id,
                        'urutan' => $urutan,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbStepProses
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'sub_proses_id' => $sub_proses_id,
                        'urutan' => $urutan,
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
