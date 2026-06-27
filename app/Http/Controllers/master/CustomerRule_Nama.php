<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_cst as tbCustomer;

class CustomerRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $branch_id;
    protected $nama;

    public function __construct($id, $branch_id, $nama)
    {
        $this->id = $id;
        $this->branch_id = $branch_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbCustomer = new tbCustomer();

        // cek exist
        $res_detail = $tbCustomer
            ->where('id', '<>', $this->id)
            ->where('branch_id', $this->branch_id)
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
