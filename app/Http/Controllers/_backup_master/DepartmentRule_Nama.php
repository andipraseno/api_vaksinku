<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_div_dep as tbDepartment;

class DepartmentRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $divisi_id;
    protected $nama;

    public function __construct($id, $divisi_id, $nama)
    {
        $this->id = $id;
        $this->divisi_id = $divisi_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbDepartment = new tbDepartment();

        // cek exist
        $res_detail = $tbDepartment
            ->where('id', '<>', $this->id)
            ->where('divisi_id', $this->divisi_id)
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
