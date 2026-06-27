<?php

namespace App\Http\Controllers\loader;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;

use App\Http\Controllers\loader\AccessRule_Software;
use App\Http\Controllers\loader\AccessRule_Access;

use App\Models\tb_act_acc as tbAccess;
use App\Models\tb_act_sfr_tab as tbTab;
use App\Models\tb_act_sfr_tab_mdl as tbModule;
use App\Models\tb_act_acc_oto as tbOtorisasi;

class AccessController extends BaseController
{
    public function index(Request $request)
    {
        // parameters
        $access_id = $request->input('access_id');
        $software_id = $request->input('software_id');

        $tbAccess = new tbAccess();
        $tbTab = new tbTab();
        $tbModule = new tbModule();
        $tbOtorisasi = new tbOtorisasi();

        // cek validasi
        $rules = [
            'access_id' => ['required', new AccessRule_Access($access_id)],
            'software_id' => ['required', new AccessRule_Software($software_id)],
        ];

        $messages = [
            'access_id.required' => 'Tidak boleh kosong!',
            'software_id.required' => 'Tidak boleh kosong!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        // ambil data access
        $access = $tbAccess
            ->select(
                "id",
                "nama"
            )
            ->where("id", $access_id)
            ->where("status", 1)
            ->first();

        // ambil data tab
        $tabs = $tbModule
            ->join("{$tbTab->get_table()} AS A", "{$tbModule->get_table()}.tab_id", "=", "A.id")
            ->join("{$tbOtorisasi->get_table()} AS B", "{$tbModule->get_table()}.id", "=", "B.module_id")
            ->join("{$tbAccess->get_table()} AS C", "B.access_id", "=", "C.id")
            ->select(
                "{$tbModule->get_table()}.tab_id AS id",
                "A.nama",
                "A.icon"
            )
            ->where("{$tbModule->get_table()}.status", 1)
            ->where("A.status", 1)
            ->where("C.status", 1)
            ->where("A.software_id", $software_id)
            ->where("B.access_id", $access_id)
            ->groupBy(
                "{$tbModule->get_table()}.tab_id",
                "A.nama",
                "A.icon"
            )
            ->orderBy('A.urutan')
            ->get();

        // ambil data module
        $modules = $tbModule
            ->join("{$tbTab->get_table()} AS A", "{$tbModule->get_table()}.tab_id", "=", "A.id")
            ->join("{$tbOtorisasi->get_table()} AS B", "{$tbModule->get_table()}.id", "=", "B.module_id")
            ->join("{$tbAccess->get_table()} AS C", "B.access_id", "=", "C.id")
            ->select(
                "{$tbModule->get_table()}.id AS id",
                "{$tbModule->get_table()}.nama AS nama",
                "{$tbModule->get_table()}.link AS link",
                "{$tbModule->get_table()}.tab_id AS tab_id"
            )
            ->where("{$tbModule->get_table()}.status", 1)
            ->where("A.status", 1)
            ->where("C.status", 1)
            ->where("A.software_id", $software_id)
            ->where("B.access_id", $access_id)
            ->orderBy("A.urutan")
            ->orderBy("{$tbModule->get_table()}.urutan")
            ->get();

        // ambil token
        $payload = [
            'nama' => $access->nama,
            'issue_at' => time(),
            'issue_expired' => time() + 86400, // kadaluarsa token 24 jam
        ];

        $token = JWT::encode(
            $payload,
            config('app.jwt.key'),
            config('app.jwt.algo')
        );

        // return
        return response()->json([
            'success' => true,
            'data' => [
                'access' => $access,
                'token' => $token,
                'tabs' => $tabs,
                'modules' => $modules,
            ],
        ], 200);
    }
}
