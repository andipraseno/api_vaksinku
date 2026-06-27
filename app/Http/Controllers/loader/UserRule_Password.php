<?php

namespace App\Http\Controllers\loader;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\tb_act_usr as tbUser;

class UserRule_Password implements Rule
{
    protected $message;
    protected $nama;
    protected $password;

    public function __construct($nama, $password)
    {
        $this->nama = $nama;
        $this->password = $password;
    }

    public function passes($attribute, $value)
    {
        $tbUser = new tbUser();

        // Ambil user berdasarkan company_id dan nama
        $user = $tbUser
            ->where('nama', $this->nama)
            ->orWhere('email', $this->nama)
            ->orWhere('handphone', $this->nama)
            ->where('status', 1)
            ->first();

        // Cek user exist
        if (!$user) {
            $this->message = "Password salah!";
            return false;
        }

        // Cek password menggunakan Hash::check
        if ($this->password !== $user->password) {
            if (!Hash::check($this->password, $user->password)) {
                $this->message = "Password salah!";
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
