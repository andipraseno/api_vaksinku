<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_sfr_tab as tbTab;

class TabRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $software_id;
    protected $nama;

    public function __construct($id, $software_id, $nama)
    {
        $this->id = $id;
        $this->software_id = $software_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbTab = new tbTab();

        // cek exist
        $res_detail = $tbTab
            ->where('id', '<>', $this->id)
            ->where('software_id', $this->software_id)
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
