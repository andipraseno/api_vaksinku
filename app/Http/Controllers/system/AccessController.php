<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\AccessRule_Nama;

use App\Models\tb_act_acc as tbAccess;
use App\Models\tb_act_sfr_tab_mdl as tbModule;
use App\Models\tb_act_acc_oto as tbOtorisasi;

class AccessController extends BaseController
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

        $query = tbAccess::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query
                ->where('nama', 'like', '%' . $filterNama . '%');
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

            if (in_array($orderColumnName, ['id', 'nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'id', 'nama', 'status' => $orderColumnName,
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
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
            'data' => $data,
        ]);
    }

    //********************
    // show
    //********************
    public function show($id = "")
    {
        $tbAccess = new tbAccess();

        $post = $tbAccess
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
        $tbAccess = new tbAccess();

        $data = $tbAccess
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
        $tbAccess = new tbAccess();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new AccessRule_Nama($id, $nama)],
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
                $post = $tbAccess
                    ->create([
                        'nama' => $nama,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbAccess
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan",
                "data" => [
                    "id" => $id
                ]
            ], 200);
        }
    }

    //********************
    // otorisasi
    //********************
    public function otorisasi_show($access_id, $tab_id)
    {
        $tbModule = new tbModule();
        $tbOtorisasi = new tbOtorisasi();

        $post = $tbModule
            ->leftJoin("{$tbOtorisasi->get_table()} AS A", function ($join) use ($tbModule, $access_id) {
                $join->on("A.module_id", "=", "{$tbModule->get_table()}.id")
                    ->where("A.access_id", $access_id);
            })
            ->selectRaw("
                    IF(A.id IS NOT NULL, true, false) AS aktif,
                    {$tbModule->get_table()}.*
                ")
            ->where("{$tbModule->get_table()}.status", 1)
            ->where("{$tbModule->get_table()}.tab_id", $tab_id)
            ->orderBy("{$tbModule->get_table()}.urutan")
            ->get();

        return response()->json($post);
    }

    public function otorisasi_delete(Request $request)
    {
        $access_id = $request->input('access_id');
        $module_id = $request->input('module_id');

        $tbOtorisasi = new tbOtorisasi();

        $tbOtorisasi
            ->where('access_id', $access_id)
            ->where('module_id', $module_id)
            ->delete();

        return response()->json([
            "success" => true,
            "message" => "Data berhasil dihapus"
        ]);
    }

    public function otorisasi_save(Request $request)
    {
        $access_id = $request->input('access_id');
        $module_id = $request->input('module_id');

        $this->otorisasi_delete($request);

        $tbOtorisasi = new tbOtorisasi();

        $tbOtorisasi
            ->create([
                'access_id' => $access_id,
                'module_id' => $module_id,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan"
        ]);
    }
}
