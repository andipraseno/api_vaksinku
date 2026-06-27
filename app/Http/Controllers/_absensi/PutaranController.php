<?php

namespace App\Http\Controllers\absensi;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\tb_mst_shf_ptr_jdw as tbJadwal;
use App\Models\tb_mst_shf_ptr_jdw_mbr as tbMember;

class PutaranController extends BaseController
{
    public function index(Request $request)
    {
        $tbJadwal = new tbJadwal();

        $putaran_id = $request->input('putaran_id');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $post = $tbJadwal
            ->where("putaran_id", $putaran_id)
            ->whereRaw("year(tanggal) = '{$tahun}'")
            ->whereRaw("month(tanggal) = '{$bulan}'")
            ->orderBy('tanggal')
            ->get();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function libur(Request $request)
    {
        $tbJadwal = new tbJadwal();

        $putaran_id = $request->input('putaran_id');
        $tanggal = $request->input('tanggal');
        $keterangan = $request->input('keterangan');

        $tbJadwal
            ->where('putaran_id', $putaran_id)
            ->where('tanggal', $tanggal)
            ->delete();

        $tbJadwal
            ->create([
                'putaran_id' => $putaran_id,
                'tanggal' => $tanggal,
                'libur' => 1,
                'keterangan' => $keterangan,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan",
        ], 200);
    }

    public function kerja(Request $request)
    {
        $tbJadwal = new tbJadwal();

        $putaran_id = $request->input('putaran_id');
        $tanggal = $request->input('tanggal');
        $jam_masuk = $request->input('jam_masuk');
        $jam_pulang = $request->input('jam_pulang');

        $tbJadwal
            ->where('putaran_id', $putaran_id)
            ->where('tanggal', $tanggal)
            ->delete();

        $tbJadwal
            ->create([
                'putaran_id' => $putaran_id,
                'tanggal' => $tanggal,
                'libur' => 0,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan",
        ], 200);
    }

    public function member(Request $request)
    {
        $tbMember = new tbMember();

        $putaran_id = $request->input('putaran_id');
        $tanggal = $request->input('tanggal');

        $post = $tbMember
            ->where("putaran_id", $putaran_id)
            ->where("tanggal", $tanggal)
            ->orderBy('karyawan_id')
            ->get();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function member_clear(Request $request)
    {
        $tbMember = new tbMember();

        $putaran_id = $request->input('putaran_id');
        $tanggal = $request->input('tanggal');

        $tbMember
            ->where('putaran_id', $putaran_id)
            ->where('tanggal', $tanggal)
            ->delete();

        return response()->json([
            "success" => true,
            "message" => "Data berhasil diclear",
        ], 200);
    }

    public function member_add(Request $request)
    {
        $tbMember = new tbMember();

        $putaran_id = $request->input('putaran_id');
        $tanggal = $request->input('tanggal');
        $karyawan_id = $request->input('karyawan_id');
        $karyawan_nama = $request->input('karyawan_nama');

        $tbMember
            ->create([
                'putaran_id' => $putaran_id,
                'tanggal' => $tanggal,
                'karyawan_id' => $karyawan_id,
                'karyawan_nama' => $karyawan_nama,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan",
        ], 200);
    }
}
