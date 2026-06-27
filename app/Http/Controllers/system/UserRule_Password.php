<?php

namespace App\Http\Controllers\system;

use Illuminate\Contracts\Validation\Rule;

class UserRule_Password implements Rule
{
    protected $message;
    protected $password;
    protected $password2;

    public function __construct($password, $password2)
    {
        $this->password = $password;
        $this->password2 = $password2;
    }

    public function passes($attribute, $value)
    {
        $hasil = true;

        if ($this->password != $this->password2) {
            $this->message = "Password tidak sama!";
            $hasil = false;
        }

        return $hasil;
    }

    public function message()
    {
        return $this->message;
    }
}
