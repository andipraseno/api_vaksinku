<?php

namespace App\Http\Controllers\hrd;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\tb_trs_kry as tbKaryawan;

class KaryawanController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterStatus = $request->input('status');

        $query = tbKaryawan::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_trs_kry.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_trs_kry.nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_trs_kry.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['id', 'nama', 'nip', 'handphone', 'posisi', 'status'])) {
                $field = match ($orderColumnName) {
                    'id' => 'tb_trs_kry.id',
                    'nama' => 'tb_trs_kry.nama',
                    'nip' => 'tb_trs_kry.nip',
                    'handphone' => 'tb_trs_kry.handphone',
                    'posisi' => 'tb_trs_kry.posisi',
                    'status' => 'tb_trs_kry.status',
                    default => 'tb_trs_kry.nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('tb_trs_kry.nama');
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
        $tbKaryawan = new tbKaryawan();

        $post = $tbKaryawan
            ->where('id', $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $foto = asset('storage/' . $post->foto);
        $ktp = asset('storage/' . $post->ktp);
        $kk = asset('storage/' . $post->kk);

        return response()->json([
            "data" => $post,
            "foto" => $foto,
            "ktp" => $ktp,
            "kk" => $kk,
        ]);
    }

    public function combo()
    {
        $tbKaryawan = new tbKaryawan();

        $data = $tbKaryawan
            ->select(
                'id',
                'nip',
                'nama'
            )
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbKaryawan = new tbKaryawan();

        $id = $request->input('id');
        $nip = $request->input('nip');
        $nama = $request->input('nama');
        $nama_panggilan = $request->input('nama_panggilan');
        $jenis_kelamin_id = $request->input('jenis_kelamin_id');
        $tempat_lahir = $request->input('tempat_lahir');
        $tgl_lahir = $request->input('tgl_lahir');
        $agama_id = $request->input('agama_id');
        $golongan_darah_id = $request->input('golongan_darah_id');
        $status_perkawinan_id = $request->input('status_perkawinan_id');
        $nktp = $request->input('nktp');
        $npwp = $request->input('npwp');
        $status_pajak_id = $request->input('status_pajak_id');
        $nbpjs_ketenagakerjaan = $request->input('nbpjs_ketenagakerjaan');
        $nbpjs_kesehatan = $request->input('nbpjs_kesehatan');
        $faskes = $request->input('faskes');
        $sim = $request->input('sim');
        $alamat_lengkap = $request->input('alamat_lengkap');
        $alamat = $request->input('alamat');
        $rt = $request->input('rt');
        $rw = $request->input('rw');
        $kelurahan = $request->input('kelurahan');
        $kecamatan = $request->input('kecamatan');
        $kota = $request->input('kota');
        $provinsi = $request->input('provinsi');
        $kode_pos = $request->input('kode_pos');
        $handphone = $request->input('handphone');
        $email = $request->input('email');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => 'required',
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
                $post = $tbKaryawan
                    ->create([
                        'nip' => $nip,
                        'nama' => $nama,
                        'nama_panggilan' => $nama_panggilan,
                        'jenis_kelamin_id' => $jenis_kelamin_id,
                        'tempat_lahir' => $tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'agama_id' => $agama_id,
                        'golongan_darah_id' => $golongan_darah_id,
                        'status_perkawinan_id' => $status_perkawinan_id,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'status_pajak_id' => $status_pajak_id,
                        'nbpjs_ketenagakerjaan' => $nbpjs_ketenagakerjaan,
                        'nbpjs_kesehatan' => $nbpjs_kesehatan,
                        'faskes' => $faskes,
                        'sim' => $sim,
                        'alamat_lengkap' => $alamat_lengkap,
                        'alamat' => $alamat,
                        'rt' => $rt,
                        'rw' => $rw,
                        'kelurahan' => $kelurahan,
                        'kecamatan' => $kecamatan,
                        'kota' => $kota,
                        'provinsi' => $provinsi,
                        'kode_pos' => $kode_pos,
                        'handphone' => $handphone,
                        'email' => $email,
                        'status' => $status,
                        'created_by' => $by,
                    ]);
            } else {
                $post = $tbKaryawan
                    ->where('id', $id)
                    ->update([
                        'nip' => $nip,
                        'nama' => $nama,
                        'nama_panggilan' => $nama_panggilan,
                        'jenis_kelamin_id' => $jenis_kelamin_id,
                        'tempat_lahir' => $tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'agama_id' => $agama_id,
                        'golongan_darah_id' => $golongan_darah_id,
                        'status_perkawinan_id' => $status_perkawinan_id,
                        'nktp' => $nktp,
                        'npwp' => $npwp,
                        'status_pajak_id' => $status_pajak_id,
                        'nbpjs_ketenagakerjaan' => $nbpjs_ketenagakerjaan,
                        'nbpjs_kesehatan' => $nbpjs_kesehatan,
                        'faskes' => $faskes,
                        'sim' => $sim,
                        'alamat_lengkap' => $alamat_lengkap,
                        'alamat' => $alamat,
                        'rt' => $rt,
                        'rw' => $rw,
                        'kelurahan' => $kelurahan,
                        'kecamatan' => $kecamatan,
                        'kota' => $kota,
                        'provinsi' => $provinsi,
                        'kode_pos' => $kode_pos,
                        'handphone' => $handphone,
                        'email' => $email,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function upload_foto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'id.required' => 'ID karyawan wajib diisi',
            'foto.required' => 'Foto wajib diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->input('id');

        $karyawan = tbKaryawan::where('id', $id)->first();

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        // hapus foto lama jika ada
        if (!empty($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        // upload foto baru
        $file = $request->file('foto');

        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs(
            'karyawan',
            $filename,
            'public'
        );

        // simpan path ke database
        $karyawan->update([
            'foto' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'foto' => asset('storage/' . $path)
        ], 200);
    }

    public function upload_ktp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'ktp' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'id.required' => 'ID karyawan wajib diisi',
            'ktp.required' => 'Dokumen wajib diupload',
            'ktp.image' => 'File harus berupa gambar',
            'ktp.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
            'ktp.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->input('id');

        $karyawan = tbKaryawan::where('id', $id)->first();

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        // hapus ktp lama jika ada
        if (!empty($karyawan->ktp)) {
            Storage::disk('public')->delete($karyawan->ktp);
        }

        // upload ktp baru
        $file = $request->file('ktp');

        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs(
            'ktp',
            $filename,
            'public'
        );

        // simpan path ke database
        $karyawan->update([
            'ktp' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diupload',
            'ktp' => asset('storage/' . $path)
        ], 200);
    }

    public function upload_kk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'kk' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'id.required' => 'ID karyawan wajib diisi',
            'kk.required' => 'Dokumen wajib diupload',
            'kk.image' => 'File harus berupa gambar',
            'kk.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
            'kk.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->input('id');

        $karyawan = tbKaryawan::where('id', $id)->first();

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        // hapus kk lama jika ada
        if (!empty($karyawan->kk)) {
            Storage::disk('public')->delete($karyawan->kk);
        }

        // upload kk baru
        $file = $request->file('kk');

        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs(
            'kk',
            $filename,
            'public'
        );

        // simpan path ke database
        $karyawan->update([
            'kk' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diupload',
            'kk' => asset('storage/' . $path)
        ], 200);
    }
}
