<?php

namespace App\Http\Controllers\website;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\tb_mst_agg as tbAgreement;

class AgreementController extends BaseController
{
     public function index($language_id = "")
     {
          $tbAgreement = new tbAgreement();

          $post = $tbAgreement
               ->where('language_id', $language_id)
               ->first();

          if (empty($post)) {
               return response()->json([
                    'message' => 'Data tidak ditemukan'
               ], 404);
          }

          return response()->json(
               $post
          );
     }

     public function add(Request $request)
     {
          $language_id = $request->input('language_id');
          $catatan1 = $request->input('catatan1');
          $catatan2 = $request->input('catatan2');
          $catatan3 = $request->input('catatan3');
          $catatan4 = $request->input('catatan4');
          $catatan5 = $request->input('catatan5');
          $catatan6 = $request->input('catatan6');

          $tbAgreement = new tbAgreement();

          $tbAgreement
               ->where('language_id', $language_id)
               ->update([
                    'catatan1' => $catatan1,
                    'catatan2' => $catatan2,
                    'catatan3' => $catatan3,
                    'catatan4' => $catatan4,
                    'catatan5' => $catatan5,
                    'catatan6' => $catatan6,
               ]);

          return response()->json([
               "success" => true,
               "message" => "Data berhasil disimpan",
          ], 200);
     }
}
