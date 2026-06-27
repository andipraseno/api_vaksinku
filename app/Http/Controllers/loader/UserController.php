<?php

namespace App\Http\Controllers\loader;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;

use App\Http\Controllers\loader\UserRule_Nama;
use App\Http\Controllers\loader\UserRule_Password;

use App\Models\tb_act_usr as tbUser;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        // Get parameters
        $nama = $request->input('nama');
        $password = $request->input('password');

        // Validation rules
        $rules = [
            'nama' => ['required', new UserRule_Nama($nama)],
            'password' => ['required', new UserRule_Password($nama, $password)],
        ];

        $messages = [
            'nama.required' => 'Tidak boleh kosong!',
            'password.required' => 'Tidak boleh kosong!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        // Additional user data
        $tbUser = new tbUser();

        $post_user = $tbUser
            ->select(
                'id',
                'nama',
                'handphone',
                'email',
                'access_id'
            )
            ->where("nama", $nama)
            ->orWhere("email", $nama)
            ->orWhere("handphone", $nama)
            ->where("status", 1)
            ->first();

        // Generate JWT token
        $payload = [
            'id' => $post_user->id,
            'nama' => $post_user->nama,
            'issue_at' => time(),
            'issue_expired' => time() + 86400, // kadaluarsa token 24 jam
        ];

        $token = JWT::encode(
            $payload,
            config('app.jwt.key'),
            config('app.jwt.algo')
        );

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $post_user,
                'token' => $token,
            ],
        ], 200);
    }
}
