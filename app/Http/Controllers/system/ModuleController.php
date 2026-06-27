<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\ModuleRule_Nama;

use App\Models\tb_act_sfr_tab_mdl as tbModule;
use App\Models\tb_act_sfr_tab as tbTab;

class ModuleController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        $filterNama = $request->input('nama');
        $filterSoftwareId = $request->input('software_id');
        $filterTabId = $request->input('tab_id');
        $filterStatus = $request->input('status');

        $query = tbModule::query()
            ->join('tb_act_sfr_tab as tab', 'tb_act_sfr_tab_mdl.tab_id', '=', 'tab.id')
            ->select(
                'tb_act_sfr_tab_mdl.*',
                'tab.urutan as tab_urutan',
                'tab.nama as tab_nama'
            );

        $recordsTotal = tbModule::count();

        // Pencarian global
        if (!empty($searchValue)) {
            $query->where('tb_act_sfr_tab_mdl.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter nama dan link
        if (!empty($filterNama)) {
            $query->where(function ($q) use ($filterNama) {
                $q->where('tb_act_sfr_tab_mdl.nama', 'like', '%' . $filterNama . '%')
                    ->orWhere('tb_act_sfr_tab_mdl.link', 'like', '%' . $filterNama . '%');
            });
        }

        $query->where('tab.software_id', $filterSoftwareId);

        if (!empty($filterTabId)) {
            $query->where('tb_act_sfr_tab_mdl.tab_id', $filterTabId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_act_sfr_tab_mdl.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['tab_urutan', 'urutan', 'nama', 'link', 'tab_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'tab_urutan' => 'tab.urutan',
                    'tab_nama' => 'tab.nama',
                    'urutan', 'nama', 'link', 'status' => 'tb_act_sfr_tab_mdl.' . $orderColumnName,
                    default => 'tb_act_sfr_tab_mdl.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            // Default order
            $query->orderBy('tab.urutan')
                ->orderBy('tb_act_sfr_tab_mdl.urutan');
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
        $tbModule = new tbModule();
        $tbTab = new tbTab();

        $post = $tbModule
            ->join("{$tbTab->getTable()} as A", "{$tbModule->getTable()}.tab_id", '=', 'A.id')
            ->select(
                "{$tbModule->getTable()}.*",
                "A.nama as tab_nama",
                "A.software_id"
            )
            ->where("{$tbModule->getTable()}.id", $id)
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
        $tbModule = new tbModule();

        $data = $tbModule
            ->select(
                'id',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function combo_by_tab($tab_id)
    {
        $tbModule = new tbModule();
        $tbTab = new tbTab();

        $data = $tbModule
            ->join("{$tbTab->get_table()} AS A", "{$tbModule->get_table()}.tab_id", "=", "A.id")
            ->select(
                "{$tbModule->get_table()}.id",
                "{$tbModule->get_table()}.nama",
            )
            ->where("{$tbModule->get_table()}.status", 1)
            ->where("{$tbModule->get_table()}.tab_id", $tab_id)
            ->orderBy("{$tbModule->get_table()}.nama")
            ->get();

        return response()->json($data);
    }

    public function combo_by_software($software_id)
    {
        $tbModule = new tbModule();
        $tbTab = new tbTab();

        $data = $tbModule
            ->join("{$tbTab->get_table()} AS A", "{$tbModule->get_table()}.tab_id", "=", "A.id")
            ->select(
                "{$tbModule->get_table()}.id",
                "{$tbModule->get_table()}.nama",
            )
            ->where("{$tbModule->get_table()}.status", 1)
            ->where("A.software_id", $software_id)
            ->orderBy("{$tbModule->get_table()}.nama")
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbModule = new tbModule();

        $id = $request->input('id');
        $tab_id = $request->input('tab_id');
        $nama = $request->input('nama');
        $link = $request->input('link');
        $urutan = $request->input('urutan');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new ModuleRule_Nama($id, $tab_id, $nama)],
            'tab_id' => 'required',
            'link' => 'required',
            'urutan' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'tab_id.required' => 'Belum dipilih!',
            'link.required' => 'Belum dipilih!',
            'urutan.required' => 'Belum dipilih!',
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
                $post = $tbModule
                    ->create([
                        'tab_id' => $tab_id,
                        'nama' => $nama,
                        'link' => $link,
                        'urutan' => $urutan,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id  = $post->id;
            } else {
                $tbModule
                    ->where('id', $id)
                    ->update([
                        'tab_id' => $tab_id,
                        'nama' => $nama,
                        'link' => $link,
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
