<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\TabRule_Nama;

use App\Models\tb_act_sfr_tab as tbTab;

class TabController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterSoftwareId = $request->input('software_id');
        $filterStatus = $request->input('status');

        $query = tbTab::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('nama', 'like', '%' . $filterNama . '%');
        }

        $query->where('software_id', $filterSoftwareId);

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

            if (in_array($orderColumnName, ['urutan', 'id', 'nama', 'icon', 'status'])) {
                $field = match ($orderColumnName) {
                    'urutan', 'id', 'nama', 'icon', 'status' => $orderColumnName,
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('urutan');
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
    public function show($id)
    {
        $tbTab = new tbTab();

        $post = $tbTab
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
        $tbTab = new tbTab();

        $data = $tbTab
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($data);
    }

    public function combo_by_software($software_id)
    {
        $tbTab = new tbTab();

        $data = $tbTab
            ->where('status', 1)
            ->where('software_id', $software_id)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbTab = new tbTab();

        $id = $request->input('id');
        $software_id = $request->input('software_id');
        $nama = $request->input('nama');
        $icon = $request->input('icon');
        $urutan = $request->input('urutan');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new TabRule_Nama($id, $software_id, $nama)],
            'software_id' => 'required',
            'urutan' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'software_id.required' => 'Belum dipilih!',
            'urutan.required' => 'Tidak boleh kosong!',
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
                $post = $tbTab
                    ->create([
                        'software_id' => $software_id,
                        'nama' => $nama,
                        'icon' => $icon,
                        'urutan' => $urutan,
                        'status' => $status,
                        'created_by' => $by,
                    ]);
            } else {
                $post = $tbTab
                    ->where('id', $id)
                    ->update([
                        'software_id' => $software_id,
                        'nama' => $nama,
                        'icon' => $icon,
                        'urutan' => $urutan,
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
}
