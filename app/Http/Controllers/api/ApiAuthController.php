<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email Tidak Valid',
            'password.required' => 'Password Harus Diisi',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => false,
                'validation' => false,
                'message' => 'Email Atau Password Tidak Valid',
            ], 401);
        }

        $redirect = '';
        if ($user->role == '1') {
            $redirect = 'admin';
        } else if ($user->role == '2') {
            $redirect = 'user';
        }

        return response()->json([
            'status' => true,
            'redirect' => $redirect,
            'message' => 'Login Berhasil, Selamat Datang Admin',
            'remember_token' => $user->remember_token,
        ], 200);
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'jabatan' => 'required',
        ], [
            'name.required' => 'Nama Harus Diisi',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email Tidak Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'password.required' => 'Password Harus Diisi',
            'password.min' => 'Password Minimal 6 Karakter',
            'password.confirmed' => 'Password Tidak Sama',
            'jabatan.required' => 'Jabatan Harus Diisi',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'role' => '0',
            'remember_token' => Str::random(60),
        ];

        $user = User::create($data);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Mendaftarkan Akun, Coba Lagi Nanti',
                'validation' => false,
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Akun Berhasil, Silahkan Login',
            'redirect' => '/',
            'remember_token' => $data['remember_token'],
        ], 201);
    }
}
