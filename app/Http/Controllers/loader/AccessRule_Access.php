<?php

namespace App\Http\Controllers\loader;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_acc as tbAccess;

class AccessRule_Access implements Rule
{
    protected $message;
    protected $access_id;

    public function __construct($access_id)
    {
        $this->access_id = $access_id;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbAccess = new tbAccess();

        // cek exist
        $res_detail = $tbAccess
            ->where('id', $this->access_id)
            ->where('status', 1)
            ->get();

        if (count($res_detail) <= 0) {
            $this->message = "Level tidak terdaftar!";
            $hasil = false;
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
