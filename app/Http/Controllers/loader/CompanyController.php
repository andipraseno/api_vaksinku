<?php

namespace App\Http\Controllers\loader;

use Illuminate\Routing\Controller as BaseController;

use App\Models\tb_act_cpy as tbCompany;

class CompanyController extends BaseController
{
    public function index($company_id)
    {
        $tbCompany = new tbCompany();

        $data = $tbCompany
            ->select(
                'id',
                'nama',
                'nama_formal',
                'bidang_usaha',
                'npwp',
                'kode',
                'alamat',
                'telepon',
                'handphone',
                'email',
                'website',
                'maps',
                'instagram',
                'logo',
            )
            ->where("id", $company_id)
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
}
