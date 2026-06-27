<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_prd_kat_jns as tbJenisProduk;

class JenisProdukRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $kategori_id;
    protected $nama;

    public function __construct($id, $kategori_id, $nama)
    {
        $this->id = $id;
        $this->kategori_id = $kategori_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbJenisProduk = new tbJenisProduk();

        // cek exist
        $res_detail = $tbJenisProduk
            ->where('id', '<>', $this->id)
            ->where('kategori_id', $this->kategori_id)
            ->where('nama', $this->nama)
            ->get();

        if (count($res_detail) > 0) {
            $this->message = "Sudah terdaftar!";
            $hasil = false;
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
