<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "password" => ["required", "string", "min:8"],
        ]);

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
        ]);

        return response()->json(
            [
                "message" => "Register berhasil!",
            ],
            201
        );
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "password" => ["required", "string", "min:8"],
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return response()->json(
                [
                    "message" => "Halo, {$user->name}!",
                ],
                200
            );
        }

        return response()->json(
            [
                "message" => "Login gagal",
            ],
            401
        );
    }
}
