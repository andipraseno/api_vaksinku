<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_div_dep_bag_reg_sek as tbSeksi;

class SeksiRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $regu_id;
    protected $nama;

    public function __construct($id, $regu_id, $nama)
    {
        $this->id = $id;
        $this->regu_id = $regu_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbSeksi = new tbSeksi();

        // cek exist
        $res_detail = $tbSeksi
            ->where('id', '<>', $this->id)
            ->where('regu_id', $this->regu_id)
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
