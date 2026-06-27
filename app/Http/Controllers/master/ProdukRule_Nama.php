<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_prd as tbProduk;

class ProdukRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $jenis_id;
    protected $nama;

    public function __construct($id, $jenis_id, $nama)
    {
        $this->id = $id;
        $this->jenis_id = $jenis_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbProduk = new tbProduk();

        // cek exist
        $res_detail = $tbProduk
            ->where('id', '<>', $this->id)
            ->where('jenis_id', $this->jenis_id)
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
