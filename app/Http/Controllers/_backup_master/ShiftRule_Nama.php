<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_div_dep_bag_reg_sek_shf as tbShift;

class ShiftRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $seksi_id;
    protected $nama;

    public function __construct($id, $seksi_id, $nama)
    {
        $this->id = $id;
        $this->seksi_id = $seksi_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbShift = new tbShift();

        // cek exist
        $res_detail = $tbShift
            ->where('id', '<>', $this->id)
            ->where('seksi_id', $this->seksi_id)
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
