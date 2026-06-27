<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_sfr as tbSoftware;

class SoftwareRule_Kode implements Rule
{
    protected $message;
    protected $id;
    protected $kode;

    public function __construct($id, $kode)
    {
        $this->id = $id;
        $this->kode = $kode;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbSoftware = new tbSoftware();

        // cek exist
        $res_detail = $tbSoftware
            ->where('id', '<>', $this->id)
            ->where('kode', $this->kode)
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
