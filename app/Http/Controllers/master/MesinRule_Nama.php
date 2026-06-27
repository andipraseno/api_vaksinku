<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_msn as tbMesin;

class MesinRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $unit_id;
    protected $nama;

    public function __construct($id, $unit_id, $nama)
    {
        $this->id = $id;
        $this->unit_id = $unit_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbMesin = new tbMesin();

        // cek exist
        $res_detail = $tbMesin
            ->where('id', '<>', $this->id)
            ->where('unit_id', $this->unit_id)
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
