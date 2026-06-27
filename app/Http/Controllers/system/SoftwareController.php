<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\SoftwareRule_Nama;
use App\Http\Controllers\system\SoftwareRule_Kode;

use App\Models\tb_act_sfr as tbSoftware;

class SoftwareController extends BaseController
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

        $query = tbSoftware::query()
            ->select(
                "id",
                "nama",
                "kode",
                "tagline",
                "copyright",
                "developer",
                "versi",
                "status"
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query
                ->where('nama', 'like', '%' . $filterNama . '%')
                ->orWhere('kode', 'like', '%' . $filterNama . '%')
                ->orWhere('tagline', 'like', '%' . $filterNama . '%')
                ->orWhere('copyright', 'like', '%' . $filterNama . '%')
                ->orWhere('developer', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['id', 'nama', 'kode', 'tagline', 'copyright', 'developer', 'versi', 'status'])) {
                $field = match ($orderColumnName) {
                    'id', 'nama', 'kode', 'tagline', 'copyright', 'developer', 'versi', 'status' => $orderColumnName,
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            // Default order
            $query->orderBy('nama');
        }

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'success' => true,
            'data' => $data,
        ]);
    }

    //********************
    // show
    //********************
    public function show($id)
    {
        $tbSoftware = new tbSoftware();

        $post = $tbSoftware
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json(
            [
                "success" => true,
                "data" => $post
            ]
        );
    }

    public function combo()
    {
        $tbSoftware = new tbSoftware();

        $data = $tbSoftware
            ->select(
                "id",
                "nama"
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbSoftware = new tbSoftware();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $tagline = $request->input('tagline');
        $copyright = $request->input('copyright');
        $developer = $request->input('developer');
        $versi = $request->input('versi');
        $status = $request->input('status');
        $by = $request->input('by');
        $opr = "disimpan";

        // cek error
        $errList = array(
            'nama' => ['required', new SoftwareRule_Nama($id, $nama)],
            'kode' => ['required', new SoftwareRule_Kode($id, $kode)],
            'versi' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
            'versi.required' => 'Tidak boleh kosong!',
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
                $post = $tbSoftware
                    ->create([
                        'nama' => $nama,
                        'kode' => $kode,
                        'tagline' => $tagline,
                        'copyright' => $copyright,
                        'developer' => $developer,
                        'versi' => $versi,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbSoftware
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'kode' => $kode,
                        'tagline' => $tagline,
                        'copyright' => $copyright,
                        'developer' => $developer,
                        'versi' => $versi,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);

                $opr = "diubah";
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil " . $opr,
                "id" => $id
            ], 200);
        }
    }
}
