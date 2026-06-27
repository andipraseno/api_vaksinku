<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_div_dep_bag_reg as tbRegu;

class ReguRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $bagian_id;
    protected $nama;

    public function __construct($id, $bagian_id, $nama)
    {
        $this->id = $id;
        $this->bagian_id = $bagian_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbRegu = new tbRegu();

        // cek exist
        $res_detail = $tbRegu
            ->where('id', '<>', $this->id)
            ->where('bagian_id', $this->bagian_id)
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
