<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_div_dep_bag as tbBagian;

class BagianRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $department_id;
    protected $nama;

    public function __construct($id, $department_id, $nama)
    {
        $this->id = $id;
        $this->department_id = $department_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbBagian = new tbBagian();

        // cek exist
        $res_detail = $tbBagian
            ->where('id', '<>', $this->id)
            ->where('department_id', $this->department_id)
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
