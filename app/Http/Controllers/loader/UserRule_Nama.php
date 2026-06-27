<?php

namespace App\Http\Controllers\loader;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_usr as tbUser;

class UserRule_Nama implements Rule
{
    protected $message;
    protected $nama;

    public function __construct($nama)
    {
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbUser = new tbUser();

        // cek exist
        $res_detail = $tbUser
            ->where('nama', $this->nama)
            ->orWhere('email', $this->nama)
            ->orWhere('handphone', $this->nama)
            ->where('status', 1)
            ->get();

        if (count($res_detail) <= 0) {
            $this->message = "User tidak terdaftar!";
            $hasil = false;
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
