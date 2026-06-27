<?php

namespace App\Http\Controllers\absensi;

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

    public function process(Request $request)
    {
        $tgl_dari = $request->input('tgl_dari');
        $tgl_sampai = $request->input('tgl_sampai');

        // =========================================
        // VALIDASI
        // =========================================
        if (empty($tgl_dari) || empty($tgl_sampai)) {

            return response()->json([
                "success" => false,
                "message" => "Tanggal tidak boleh kosong",
            ], 422);
        }

        DB::beginTransaction();

        try {

            // =========================================
            // CLEAR DATA REKAP TERLEBIH DAHULU
            // =========================================
            tbRekap::whereDate('tanggal', '>=', $tgl_dari)
                ->whereDate('tanggal', '<=', $tgl_sampai)
                ->delete();

            // =========================================
            // AMBIL SELURUH KARYAWAN
            // =========================================
            $karyawanList = DB::table('tb_trs_kry')
                ->select([
                    'id',
                    'nama',
                    'nip',
                ])
                ->orderBy('nama')
                ->get();

            // =========================================
            // LOOPING TANGGAL
            // =========================================
            $periode = \Carbon\CarbonPeriod::create(
                $tgl_dari,
                $tgl_sampai
            );

            foreach ($periode as $tanggal) {

                $tgl = $tanggal->format('Y-m-d');

                // =====================================
                // AMBIL REKAP ABSEN HARI INI
                // =====================================
                $absensi = tbAbsensi::query()
                    ->join('tb_trs_kry as A', 'tb_trs_abs_raw.employeeId', '=', 'A.nip')

                    ->select([
                        'A.id as karyawan_id',

                        'A.nama as karyawan_nama',

                        'A.nip',

                        DB::raw('DATE(tb_trs_abs_raw.tanggalAbsen) as tanggal'),

                        DB::raw('MIN(tb_trs_abs_raw.jamAbsen) as jam_masuk'),

                        DB::raw('MAX(tb_trs_abs_raw.jamAbsen) as jam_pulang'),
                    ])

                    ->whereDate(
                        'tb_trs_abs_raw.tanggalAbsen',
                        '=',
                        $tgl
                    )

                    ->groupBy(
                        'A.id',
                        'A.nama',
                        'A.nip',
                        DB::raw('DATE(tb_trs_abs_raw.tanggalAbsen)')
                    )

                    ->get()

                    ->keyBy('nip');

                // =====================================
                // LOOPING SEMUA KARYAWAN
                // =====================================
                foreach ($karyawanList as $karyawan) {

                    $row = $absensi[$karyawan->nip] ?? null;

                    tbRekap::create([

                        'karyawan_id' => $karyawan->id,

                        'karyawan_nama' => $karyawan->nama,

                        'tanggal' => $tgl,

                        // =============================
                        // JIKA ADA ABSEN
                        // =============================
                        'jam_masuk' => $row && !empty($row->jam_masuk)
                            ? $tgl . ' ' . $row->jam_masuk
                            : null,

                        'jam_pulang' => $row && !empty($row->jam_pulang)
                            ? $tgl . ' ' . $row->jam_pulang
                            : null,

                        // =============================
                        // DEFAULT KOSONG
                        // =============================
                        'kode_absen_id' => null,

                        'keterangan' => null,

                        'created_by' => auth()->user()->name ?? 'SYSTEM',

                        'created_at' => now(),

                        'updated_by' => auth()->user()->name ?? 'SYSTEM',

                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Proses rekap absensi berhasil",
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
