<?php

namespace App\Http\Controllers\laporan;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\tb_trs_abs_raw as tbAbsensi;
use App\Models\tb_trs_abs_rkp as tbRekap;

class KehadiranController extends BaseController
{
    public function index(Request $request)
    {
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);

        $searchValue = $request->input('search.value');

        // =========================================
        // FILTER
        // =========================================
        $filterNama = $request->input('nama');
        $filterTglDari = $request->input('tgl_dari');
        $filterTglSampai = $request->input('tgl_sampai');

        // =========================================
        // QUERY DARI TABEL REKAP
        // =========================================
        $query = tbRekap::query()
            ->select([
                'id',
                'karyawan_id',
                'karyawan_nama',

                DB::raw('DATE(tanggal) as tanggalAbsen'),

                DB::raw("
                    CASE
                        WHEN jam_masuk IS NOT NULL
                        THEN TIME_FORMAT(jam_masuk, '%H:%i:%s')
                        ELSE NULL
                    END as jamMasuk
                "),

                DB::raw("
                    CASE
                        WHEN jam_pulang IS NOT NULL
                        THEN TIME_FORMAT(jam_pulang, '%H:%i:%s')
                        ELSE NULL
                    END as jamPulang
                "),

                DB::raw("
                    CASE
                        WHEN jam_masuk IS NOT NULL
                        AND jam_pulang IS NOT NULL
                        THEN 2
                        WHEN jam_masuk IS NOT NULL
                        OR jam_pulang IS NOT NULL
                        THEN 1
                        ELSE 0
                    END as totalScan
                "),
            ]);

        // =========================================
        // FILTER TANGGAL DARI
        // =========================================
        if (!empty($filterTglDari)) {

            $query->whereDate(
                'tanggal',
                '>=',
                $filterTglDari
            );
        }

        // =========================================
        // FILTER TANGGAL SAMPAI
        // =========================================
        if (!empty($filterTglSampai)) {

            $query->whereDate(
                'tanggal',
                '<=',
                $filterTglSampai
            );
        }

        // =========================================
        // SEARCH GLOBAL DATATABLE
        // =========================================
        if (!empty($searchValue)) {

            $query->where(function ($q) use ($searchValue) {

                $q->where(
                    'karyawan_nama',
                    'like',
                    '%' . $searchValue . '%'
                );
            });
        }

        // =========================================
        // FILTER NAMA
        // =========================================
        if (!empty($filterNama)) {

            $query->where(
                'karyawan_nama',
                'like',
                '%' . $filterNama . '%'
            );
        }

        // =========================================
        // TOTAL
        // =========================================
        $recordsTotal = (clone $query)->count();

        $recordsFiltered = $recordsTotal;

        // =========================================
        // SORTING
        // =========================================
        $columns = $request->input('columns', []);
        $orders = $request->input('order', []);

        $allowedSort = [
            'karyawan_nama' => 'karyawan_nama',
            'tanggalAbsen'  => 'tanggal',
            'jamMasuk'      => 'jam_masuk',
            'jamPulang'     => 'jam_pulang',
            'totalScan'     => 'id',
        ];

        if (!empty($orders)) {

            foreach ($orders as $order) {

                $columnIndex = $order['column'] ?? null;

                $dir = $order['dir'] ?? 'asc';

                if (
                    isset($columns[$columnIndex]) &&
                    isset($columns[$columnIndex]['data'])
                ) {

                    $columnName = $columns[$columnIndex]['data'];

                    if (isset($allowedSort[$columnName])) {

                        $query->orderBy(
                            $allowedSort[$columnName],
                            $dir
                        );
                    }
                }
            }
        } else {

            // =====================================
            // DEFAULT SORT
            // =====================================
            $query
                ->orderBy('karyawan_nama', 'asc')
                ->orderBy('tanggal', 'asc');
        }

        // =========================================
        // PAGINATION
        // =========================================
        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        // =========================================
        // RESPONSE
        // =========================================
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function rekap(Request $request)
    {
        $draw = (int) $request->input('draw');

        $start = (int) $request->input('start', 0);

        $length = (int) $request->input('length', 25);

        $searchValue = $request->input('search.value');

        // =====================================================
        // FILTER
        // =====================================================
        $filterNama = $request->input('nama');

        $filterTglDari = $request->input('tgl_dari');

        $filterTglSampai = $request->input('tgl_sampai');

        // =====================================================
        // QUERY REKAP TOTAL KEHADIRAN
        // =====================================================
        $query = tbRekap::query()
            ->select([
                'karyawan_id',

                'karyawan_nama',

                // =============================================
                // TOTAL HADIR
                // =============================================
                DB::raw("
                    SUM(
                        CASE
                            WHEN jam_masuk IS NOT NULL
                            OR jam_pulang IS NOT NULL
                            THEN 1
                            ELSE 0
                        END
                    ) as total_hadir
                "),

                // =============================================
                // TOTAL TIDAK HADIR
                // =============================================
                DB::raw("
                    SUM(
                        CASE
                            WHEN jam_masuk IS NULL
                            AND jam_pulang IS NULL
                            THEN 1
                            ELSE 0
                        END
                    ) as total_tidak_hadir
                "),

                // =============================================
                // TOTAL PARSIAL
                // =============================================
                DB::raw("
                    SUM(
                        CASE
                            WHEN (
                                jam_masuk IS NOT NULL
                                AND jam_pulang IS NULL
                            )
                            OR (
                                jam_masuk IS NULL
                                AND jam_pulang IS NOT NULL
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) as total_parsial
                "),

                // =============================================
                // TOTAL HARI
                // =============================================
                DB::raw("
                    COUNT(*) as total_hari
                "),
            ]);

        // =====================================================
        // FILTER TANGGAL DARI
        // =====================================================
        if (!empty($filterTglDari)) {

            $query->whereDate(
                'tanggal',
                '>=',
                $filterTglDari
            );
        }

        // =====================================================
        // FILTER TANGGAL SAMPAI
        // =====================================================
        if (!empty($filterTglSampai)) {

            $query->whereDate(
                'tanggal',
                '<=',
                $filterTglSampai
            );
        }

        // =====================================================
        // SEARCH GLOBAL
        // =====================================================
        if (!empty($searchValue)) {

            $query->where(function ($q) use ($searchValue) {

                $q->where(
                    'karyawan_nama',
                    'like',
                    '%' . $searchValue . '%'
                );
            });
        }

        // =====================================================
        // FILTER NAMA
        // =====================================================
        if (!empty($filterNama)) {

            $query->where(
                'karyawan_nama',
                'like',
                '%' . $filterNama . '%'
            );
        }

        // =====================================================
        // GROUP BY
        // =====================================================
        $query->groupBy(
            'karyawan_id',
            'karyawan_nama'
        );

        // =====================================================
        // TOTAL DATA
        // =====================================================
        $recordsTotal = (clone $query)->get()->count();

        $recordsFiltered = $recordsTotal;

        // =====================================================
        // SORTING
        // =====================================================
        $columns = $request->input('columns', []);

        $orders = $request->input('order', []);

        $allowedSort = [
            'karyawan_nama'    => 'karyawan_nama',
            'total_hadir'      => 'total_hadir',
            'total_tidak_hadir' => 'total_tidak_hadir',
            'total_parsial'    => 'total_parsial',
            'total_hari'       => 'total_hari',
        ];

        if (!empty($orders)) {

            foreach ($orders as $order) {

                $columnIndex = $order['column'] ?? null;

                $dir = $order['dir'] ?? 'asc';

                if (
                    isset($columns[$columnIndex]) &&
                    isset($columns[$columnIndex]['data'])
                ) {

                    $columnName = $columns[$columnIndex]['data'];

                    if (isset($allowedSort[$columnName])) {

                        $query->orderBy(
                            $allowedSort[$columnName],
                            $dir
                        );
                    }
                }
            }
        } else {

            // =============================================
            // DEFAULT SORT
            // =============================================
            $query->orderBy('karyawan_nama', 'asc');
        }

        // =====================================================
        // PAGINATION
        // =====================================================
        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        // =====================================================
        // RESPONSE
        // =====================================================
        return response()->json([
            'draw' => $draw,

            'recordsTotal' => $recordsTotal,

            'recordsFiltered' => $recordsFiltered,

            'data' => $data,
        ]);
    }
}
