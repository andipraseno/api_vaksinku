<?php

namespace App\Http\Controllers\laporan;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\tb_trs_kry as tbKaryawan;
use App\Models\tb_mst_div as tbDivisi;
use App\Models\tb_mst_div_dep as tbDepartment;
use App\Models\tb_mst_div_dep_bag as tbBagian;
use App\Models\tb_mst_div_dep_bag_reg as tbRegu;
use App\Models\tb_mst_div_dep_bag_reg_sek as tbSeksi;
use App\Models\tb_mst_div_dep_bag_reg_sek_shf as tbShift;

class StatistikKaryawanController extends BaseController
{
    public function index(Request $request)
    {
        $tbKaryawan  = new tbKaryawan();
        $tbDivisi    = new tbDivisi();
        $tbDepartment = new tbDepartment();
        $tbBagian    = new tbBagian();
        $tbRegu      = new tbRegu();
        $tbSeksi     = new tbSeksi();
        $tbShift     = new tbShift();

        // =====================================================
        // BASE QUERY
        // =====================================================
        $query = $tbKaryawan
            ->leftJoin("{$tbShift->get_table()} AS A", "{$tbKaryawan->get_table()}.shift_id", "=", "A.id")
            ->leftJoin("{$tbSeksi->get_table()} AS B", "A.seksi_id", "=", "B.id")
            ->leftJoin("{$tbRegu->get_table()} AS C", "B.regu_id", "=", "C.id")
            ->leftJoin("{$tbBagian->get_table()} AS D", "C.bagian_id", "=", "D.id")
            ->leftJoin("{$tbDepartment->get_table()} AS E", "D.department_id", "=", "E.id")
            ->leftJoin("{$tbDivisi->get_table()} AS F", "E.divisi_id", "=", "F.id");

        // =====================================================
        // FILTER
        // =====================================================
        if ($request->nama) {
            $query->where("{$tbKaryawan->get_table()}.nama", "like", "%" . $request->nama . "%");
        }

        // =====================================================
        // DETAIL
        // =====================================================
        $detail = (clone $query)
            ->select(
                "{$tbKaryawan->get_table()}.id",
                "{$tbKaryawan->get_table()}.nip",
                "{$tbKaryawan->get_table()}.nama",

                "{$tbKaryawan->get_table()}.shift_id",
                "A.nama AS shift_nama",

                "A.seksi_id",
                "B.nama AS seksi_nama",

                "B.regu_id",
                "C.nama AS regu_nama",

                "C.bagian_id",
                "D.nama AS bagian_nama",

                "D.department_id",
                "E.nama AS department_nama",

                "E.divisi_id",
                "F.nama AS divisi_nama",
            )
            ->orderBy("{$tbKaryawan->get_table()}.nama")
            ->get();

        // =====================================================
        // SUMMARY
        // =====================================================
        $summary = [
            'total_karyawan' => $detail->count(),
        ];

        // =====================================================
        // STATISTIK DIVISI
        // =====================================================
        $divisi = (clone $query)
            ->select(
                "F.id",
                "F.nama",
                DB::raw("COUNT(*) as total")
            )
            ->groupBy("F.id", "F.nama")
            ->orderBy("total", "desc")
            ->get();

        // =====================================================
        // STATISTIK DEPARTMENT
        // =====================================================
        $department = (clone $query)
            ->select(
                "E.id",
                "E.nama",
                DB::raw("COUNT(*) as total")
            )
            ->groupBy("E.id", "E.nama")
            ->orderBy("total", "desc")
            ->get();

        // =====================================================
        // STATISTIK SHIFT
        // =====================================================
        $shift = (clone $query)
            ->select(
                "A.id",
                "A.nama",
                DB::raw("COUNT(*) as total")
            )
            ->groupBy("A.id", "A.nama")
            ->orderBy("total", "desc")
            ->get();

        return response()->json([
            'summary'   => $summary,
            'divisi'    => $divisi,
            'department' => $department,
            'shift'     => $shift,
            'detail'    => $detail,
        ]);
    }
}
