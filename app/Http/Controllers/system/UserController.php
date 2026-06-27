<?php

namespace App\Http\Controllers\system;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\system\UserRule_Nama;
use App\Http\Controllers\system\UserRule_Handphone;
use App\Http\Controllers\system\UserRule_Email;
use App\Http\Controllers\system\UserRule_Password;

use App\Models\tb_act_usr as tbUser;
use App\Models\tb_act_acc as tbAccess;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $searchValue = $request->input('search.value');

        // Filter dari modal
        $filterNama = $request->input('nama');
        $filterAccessId = $request->input('access_id');
        $filterStatus = $request->input('status');

        $query = tbUser::query()
            ->leftJoin('tb_act_acc as A', 'A.id', '=', 'tb_act_usr.access_id')
            ->select([
                'tb_act_usr.*',
                'A.nama as access_nama',
            ]);

        // Hitung total semua data tanpa filter
        $recordsTotal = $query->count();

        // Filter global pencarian (kolom "nama" saja)
        if (!empty($searchValue)) {
            $query->where('tb_act_usr.nama', 'like', '%' . $searchValue . '%');
        }

        // Filter khusus dari modal
        if (!empty($filterNama)) {
            $query->where('tb_act_usr.nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterAccessId)) {
            $query->where('tb_act_usr.access_id', $filterAccessId);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $query->where('tb_act_usr.status', $filterStatus);
        }

        $recordsFiltered = $query->count();

        // sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            if (in_array($orderColumnName, ['nama', 'email', 'handphone', 'access_id', 'access_nama', 'status'])) {
                $field = match ($orderColumnName) {
                    'nama' => 'tb_act_usr.nama',
                    'email' => 'tb_act_usr.email',
                    'handphone' => 'tb_act_usr.handphone',
                    'access_id' => 'tb_act_usr.access_id',
                    'access_nama' => 'A.nama',
                    'status' => 'tb_act_usr.status',
                    default => 'nama'
                };

                $query->orderBy($field, $orderDir);
            }
        } else {
            $query->orderBy('nama');
        }

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    //********************
    // show
    //********************
    public function show($id = "")
    {
        $tbUser = new tbUser();

        $post = $tbUser
            ->where("id", $id)
            ->first();

        if (empty($post)) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($post);
    }

    public function combo()
    {
        $tbUser = new tbUser();

        $data = $tbUser
            ->where("status", 1)
            ->orderBy("nama")
            ->get();

        return response()->json($data);
    }

    //********************
    // save
    //********************
    public function add(Request $request)
    {
        $tbUser = new tbUser();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $email = $request->input('email');
        $handphone = $request->input('handphone');
        $password = '123';
        $access_id = $request->input('access_id');
        $status = $request->input('status');
        $by = $request->input('by');

        // cek error
        $errList = array(
            'nama' => ['required', new UserRule_Nama($id, $nama)],
            'handphone' => [new UserRule_Email($id, $handphone)],
            'email' => [new UserRule_Email($id, $email)],
            'access_id' => 'required',
        );

        $errMessage = array(
            'nama.required' => 'Tidak boleh kosong!',
            'access_id.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            if ($id == '') {
                $post = $tbUser
                    ->create([
                        'access_id' => $access_id,
                        'nama' => $nama,
                        'email' => $email,
                        'handphone' => $handphone,
                        'password' => $password,
                        'status' => $status,
                        'created_by' => $by,
                    ]);

                $id = $post->id;
            } else {
                $post = $tbUser
                    ->where('id', $id)
                    ->update([
                        'access_id' => $access_id,
                        'nama' => $nama,
                        'email' => $email,
                        'handphone' => $handphone,
                        'password' => $password,
                        'status' => $status,
                        'updated_by' => $by,
                    ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }

    public function password(Request $request)
    {
        $tbUser = new tbUser();

        $id = $request->input('id');
        $password = $request->input('password');
        $password2 = $request->input('password2');

        // cek error
        $errList = array(
            'password' => ['required'],
            'password2' => ['required', new UserRule_Password($password, $password2)],
        );

        $errMessage = array(
            'password.required' => 'Tidak boleh kosong!',
            'password2.required' => 'Tidak boleh kosong!',
        );

        $errResult = Validator::make(
            $request->all(),
            $errList,
            $errMessage
        );

        if ($errResult->fails()) {
            return response()->json($errResult->errors(), 400);
        } else {
            $tbUser
                ->where('id', $id)
                ->update([
                    'password' => $password,
                ]);

            return response()->json([
                "success" => true,
                "message" => "Data berhasil disimpan"
            ], 200);
        }
    }
}
