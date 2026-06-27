<?php

namespace App\Http\Controllers\loader;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_sfr as tbSoftware;

class AccessRule_Software implements Rule
{
    protected $message;
    protected $software_id;

    public function __construct($software_id)
    {
        $this->software_id = $software_id;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbSoftware = new tbSoftware();

        // cek exist
        $res_detail = $tbSoftware
            ->where('id', $this->software_id)
            ->where('status', 1)
            ->get();

        if (count($res_detail) <= 0) {
            $this->message = "Software tidak terdaftar!";
            $hasil = false;
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
