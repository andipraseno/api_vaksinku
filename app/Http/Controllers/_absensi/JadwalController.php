<?php

namespace App\Http\Controllers\absensi;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\absensi\JadwalRule_Nama;

use App\Models\tb_mst_shf as tbShift;
use App\Models\tb_mst_shf_ptr as tbJadwal;
use App\Models\tb_mst_cal as tbCalendar;

class JadwalController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterShiftId = $request->input('shift_id');
        $filterStatus = $request->input('status');

        $query = tbJadwal::query()
            ->leftJoin('tb_mst_shf as A', 'tb_mst_shf_ptr.shift_id', '=', 'A.id')
            ->select(
                'tb_mst_shf_ptr.*',
                'A.nama as shift_nama',
            );

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_mst_shf_ptr.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query
                ->where('tb_mst_shf_ptr.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterShiftId)) {
            $query
                ->where('tb_mst_shf_ptr.shift_id', 'like', '%' . $filterShiftId . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_mst_shf_ptr.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'keterangan', 'shift_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_mst_shf_ptr.nama',
                    'keterangan' => 'tb_mst_shf_ptr.keterangan',
                    'shift_nama' => 'A.nama',
                    'status' => 'tb_mst_shf_ptr.status',
                    default => 'tb_mst_shf_ptr.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_mst_shf_ptr.nama');
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
    public function show($id = "")
    {
        $tbJadwal = new tbJadwal();
        $tbShift = new tbShift();

        $post = $tbJadwal
            ->join("{$tbShift->get_table()} AS A", "A.id", "=", "{$tbJadwal->get_table()}.shift_id")
            ->select(
                "{$tbJadwal->get_table()}.*",
                "A.nama AS shift_nama"
            )
            ->where("{$tbJadwal->get_table()}.id", $id)
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
        $tbJadwal = new tbJadwal();

        $data = $tbJadwal
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function calendar(Request $request)
    {
        Carbon::setLocale('id');

        $tbCalendar = new tbCalendar();

        $tahun = $request->tahun;
        $tanggal = Carbon::create($tahun, 1, 1);
        $akhir = Carbon::create($tahun, 12, 31);

        $tbCalendar
            ->whereRaw("YEAR(tanggal) = ?", [$tahun])
            ->delete();

        while ($tanggal <= $akhir) {
            $hari = $tanggal->translatedFormat('l');

            $isLibur = in_array($hari, [
                'Minggu'
            ]) ? 1 : 0;

            $tbCalendar
                ->create([
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'hari' => $hari,
                    'libur' => $isLibur,
                ]);

            $tanggal->addDay();
        }

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan",
        ], 200);
    }

    public function add(Request $request)
    {
        $tbJadwal = new tbJadwal();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $urutan = $request->input('urutan');
        $jam_masuk = $request->input('jam_masuk');
        $jam_pulang = $request->input('jam_pulang');
        $toleransi_telat = $request->input('toleransi_telat');
        $istirahat_mulai = $request->input('istirahat_mulai');
        $istirahat_selesai = $request->input('istirahat_selesai');
        $lintas_hari = $request->input('lintas_hari');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new JadwalRule_Nama($id, $nama)],
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
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
                $post = $tbJadwal
                    ->create([
                        'nama' => $nama,
                        'urutan' => $urutan,
                        'jam_masuk' => $jam_masuk,
                        'jam_pulang' => $jam_pulang,
                        'toleransi_telat' => $toleransi_telat,
                        'istirahat_mulai' => $istirahat_mulai,
                        'istirahat_selesai' => $istirahat_selesai,
                        'lintas_hari' => $lintas_hari,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $post = $tbJadwal
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'urutan' => $urutan,
                        'jam_masuk' => $jam_masuk,
                        'jam_pulang' => $jam_pulang,
                        'toleransi_telat' => $toleransi_telat,
                        'istirahat_mulai' => $istirahat_mulai,
                        'istirahat_selesai' => $istirahat_selesai,
                        'lintas_hari' => $lintas_hari,
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
