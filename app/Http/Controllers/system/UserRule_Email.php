<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

use App\Models\tb_act_usr as tbUser;

class UserRule_Email implements Rule
{
    protected $message;
    protected $id;
    protected $email;

    public function __construct($id, $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        if ($this->email) {
            $tbUser = new tbUser();

            // cek exist
            $res_detail = $tbUser
                ->where('id', '<>', $this->id)
                ->where('email', $this->email)
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
