<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_sfr_tab_mdl as tbModule;

class ModuleRule_Nama implements Rule
{
    protected $message;
    protected $id;
    protected $tab_id;
    protected $nama;

    public function __construct($id, $tab_id, $nama)
    {
        $this->id = $id;
        $this->tab_id = $tab_id;
        $this->nama = $nama;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        $tbModule = new tbModule();

        // cek exist
        $res_detail = $tbModule
            ->where('id', '<>', $this->id)
            ->where('tab_id', $this->tab_id)
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
