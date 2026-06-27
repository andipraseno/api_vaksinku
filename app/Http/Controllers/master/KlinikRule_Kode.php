<?php

namespace App\Http\Controllers\master;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_mst_klk as tbKlinik;

class KlinikRule_Kode implements Rule
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

        $tbKlinik = new tbKlinik();

        // cek exist
        $res_detail = $tbKlinik
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
