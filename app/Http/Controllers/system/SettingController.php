<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\system\SettingRule_Nama;

use App\Models\tb_mst_set as tbSetting;
use App\Models\tb_mst_set_srd as tbSettingShared;

class SettingController extends BaseController
{
     public function index()
     {
          $tbSetting = new tbSetting();
          $tbSettingShared = new tbSettingShared();

          $post = $tbSetting
               ->first();

          $post_detail = $tbSettingShared
               ->orderBy('id')
               ->get();

          if (empty($post)) {
               return response()->json([
                    'message' => 'Data tidak ditemukan'
               ], 404);
          }

          return response()->json(
               [
                    "header" => $post,
                    "detail" => $post_detail
               ]
          );
     }

     public function add(Request $request)
     {
          DB::beginTransaction();

          try {

               $tbSetting = new tbSetting();
               $tbSettingShared = new tbSettingShared();

               $profit_share = $request->input('profit_share');
               $total_shared = $request->input('total_shared');
               $balance = $request->input('balance');
               $catatan = $request->input('catatan');
               $shared = $request->input('shared_list', []);

               // Update Setting
               $tbSetting
                    ->where('id', 1)
                    ->update([
                         'profit_share' => $profit_share,
                         'total_shared' => $total_shared,
                         'balance' => $balance,
                         'catatan' => $catatan,
                    ]);

               // Hapus detail lama
               $tbSettingShared
                    ->where('id', '<>', 1)
                    ->delete();

               // Simpan detail baru
               foreach ($shared as $item) {

                    $tbSettingShared->insert([
                         'keterangan' => $item['keterangan'] ?? '',
                         'nilai' => $item['nilai'] ?? 0,
                    ]);
               }

               DB::commit();

               return response()->json([
                    "success" => true,
                    "message" => "Data berhasil disimpan",
               ], 200);
          } catch (\Exception $e) {

               DB::rollBack();

               return response()->json([
                    "success" => false,
                    "message" => $e->getMessage(),
               ], 500);
          }
     }
}
