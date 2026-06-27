<?php

namespace App\Http\Controllers\loader;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\tb_act_sfr as tbSoftware;

class SoftwareController extends BaseController
{
    public function index($software_id)
    {
        $tbSoftware = new tbSoftware();

        $data = $tbSoftware
            ->select(
                'id',
                'nama',
                'kode',
                'tagline',
                'versi',
                'show_footer',
                'copyright',
                'developer',
                'website',
                'email',
                'handphone1',
                'handphone2',
            )
            ->where("id", $software_id)
            ->where('status', 1)
            ->first();

        if (empty($data)) {
            return response()->json([
                "success" => false,
                "data" => [],
            ], 404);
        } else {
            return response()->json([
                "success" => true,
                "data" => $data,
            ], 200);
        }
    }

    public function cek_version(Request $request)
    {
        $software_id = $request->input('software_id');
        $version = $request->input('software_version');

        $tbSoftware = new tbSoftware();

        $data = $tbSoftware
            ->select(
                "versi"
            )
            ->where("id", $software_id)
            ->where('status', 1)
            ->first();

        if (empty($data)) {
            return response()->json([
                "must_update" => false,
                "message" => "Software not found",
            ], 200);
        } else {
            if ($version != $data->versi) {
                return response()->json([
                    "must_update" => true,
                    "message" => "A new version is available",
                ], 200);
            } else {
                return response()->json([
                    "must_update" => false,
                    "message" => "You are using the latest version",
                ], 200);
            }
        }
    }
}
