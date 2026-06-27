<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_usr as tbUser;

class UserRule_Handphone implements Rule
{
    protected $message;
    protected $id;
    protected $handphone;

    public function __construct($id, $handphone)
    {
        $this->id = $id;
        $this->handphone = $handphone;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        if ($this->handphone) {
            $tbUser = new tbUser();

            // cek exist
            $res_detail = $tbUser
                ->where('id', '<>', $this->id)
                ->where('handphone', $this->handphone)
                ->get();

            if (count($res_detail) > 0) {
                $this->message = "Sudah terdaftar!";
                $hasil = false;
            }
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
