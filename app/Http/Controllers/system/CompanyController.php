<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\CompanyRule_Nama;
use App\Http\Controllers\system\CompanyRule_Kode;

use App\Models\tb_act_cpy as tbCompany;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterStatus = $request->input('status');

        $query = tbCompany::query();

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'nama_formal', 'kode', 'alamat', 'telepon', 'email', 'website', 'maps', 'instagram', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama', 'nama_formal', 'kode', 'alamat', 'telepon', 'email', 'website', 'maps', 'instagram', 'status' => $orderColumnName,
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('nama');
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
    public function show($id = null)
    {
        $tbCompany = new tbCompany();

        $post = $tbCompany
            ->where('id', $id)
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
        $tbCompany = new tbCompany();

        $data = $tbCompany
            ->select(
                'id',
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
        $tbCompany = new tbCompany();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $nama_formal = $request->input('nama_formal');
        $npwp = $request->input('npwp');
        $kode = $request->input('kode');
        $bidang_usaha = $request->input('bidang_usaha');
        $alamat = $request->input('alamat');
        $telepon = $request->input('telepon');
        $handphone = $request->input('handphone');
        $email = $request->input('email');
        $website = $request->input('website');
        $maps = $request->input('maps');
        $instagram = $request->input('instagram');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new CompanyRule_Nama($id, $nama)],
            'kode' => ['required', new CompanyRule_Kode($id, $kode)],
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'kode.required' => 'Tidak boleh kosong!',
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
                $post = $tbCompany
                    ->create([
                        'nama' => $nama,
                        'nama_formal' => $nama_formal,
                        'npwp' => $npwp,
                        'kode' => $kode,
                        'bidang_usaha' => $bidang_usaha,
                        'alamat' => $alamat,
                        'telepon' => $telepon,
                        'handphone' => $handphone,
                        'email' => $email,
                        'website' => $website,
                        'maps' => $maps,
                        'instagram' => $instagram,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $tbCompany
                    ->where('id', $id)
                    ->update([
                        'nama' => $nama,
                        'nama_formal' => $nama_formal,
                        'npwp' => $npwp,
                        'kode' => $kode,
                        'bidang_usaha' => $bidang_usaha,
                        'alamat' => $alamat,
                        'telepon' => $telepon,
                        'handphone' => $handphone,
                        'email' => $email,
                        'website' => $website,
                        'maps' => $maps,
                        'instagram' => $instagram,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan",
                "id" => $id
            ], 200);
        }
    }

    public function logo(Request $request)
    {
        // 1. Validasi file
        // $request->validate([
        //     'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // 2. Cek apakah ada file yang diupload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            // 2. Custom Nama File
            // Contoh: LOGO_NAMA-PERUSAHAAN_TIMESTAMP.png
            $slugNama = str_replace(' ', '_', strtolower($request->id));
            $extension = $file->getClientOriginalExtension(); // Ambil ekstensi asli (.jpg, .png, dll)
            $namaFileBaru = $slugNama . "_" . time() . "." . $extension;

            // 3. Simpan ke folder 'public/logos' dengan nama baru
            $path = $file->storeAs('company', $namaFileBaru, 'public');

            // 4. Update Database (jika perlu)
            $tbCompany = new tbCompany();

            $tbCompany->where('id', $request->id)
                ->update([
                    'logo' => $namaFileBaru
                ]);

            return response()->json([
                'message' => 'Logo berhasil diunggah!',
                'file_name' => $namaFileBaru,
                'path' => $path
            ]);
        }
    }

    public function logo_show($id = null)
    {
        $tbCompany = new tbCompany();

        $company = $tbCompany
            ->where('id', $id)
            ->first();

        if ($company) {
            $company->logo_url = $company->logo ? asset('storage/company/' . $company->logo) : asset('storage/empty.png');

            return response()->json($company);
        }

        return response()->json(['message' => 'Not Found'], 404);
    }
}
