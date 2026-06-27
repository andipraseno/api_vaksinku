<?php

namespace App\Http\Controllers\produksi;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_trs_prc_sub as tbSubProses;

class SubProsesRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $proses_id;
    protected $nama;

    public function __construct($id, $proses_id, $nama)
    {
        $this->id = $id;
        $this->proses_id = $proses_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbSubProses = new tbSubProses();

        // cek exist
        $res_detail = $tbSubProses
            ->where('id', '<>', $this->id)
            ->where('proses_id', $this->proses_id)
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
