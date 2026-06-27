<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_gdg_grp as tbGroupGudang;

class GroupGudangRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $nama;

    public function __construct($id, $nama)
    {
        $this->id = $id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbGroupGudang = new tbGroupGudang();

        // cek exist
        $res_detail = $tbGroupGudang
            ->where('id', '<>', $this->id)
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
