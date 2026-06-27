<?php

namespace App\Http\Controllers\produksi;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_trs_prc_sub_stp as tbStepProses;

class StepProsesRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $sub_proses_id;
    protected $nama;

    public function __construct($id, $sub_proses_id, $nama)
    {
        $this->id = $id;
        $this->sub_proses_id = $sub_proses_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbStepProses = new tbStepProses();

        // cek exist
        $res_detail = $tbStepProses
            ->where('id', '<>', $this->id)
            ->where('sub_proses_id', $this->sub_proses_id)
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
