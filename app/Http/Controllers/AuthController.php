<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function createUser(Request $req) {
        
        $rules = [
            "name" => "required",
            "password" => "required|min:8|max:15",
            "email" => "required|email:dns|unique:users,email",
            "job" => "min:3",
            "description" => "min:3"
        ];
        
        $validation = Validator::make($req->all(), $rules, []);
        if($validation->fails()) {
            return response([
                "message" => $validation->errors()->first(),
                "status" => false,
                "code" => 400
            ], 400);
        }

        $validated = $validation->safe()->only(["name", "password", "email", "job", "description"]);
        $user = new User($validated);
        $user->password = Hash::make($req->json("password"));
        $user->save();
        return response([
            "message" => "Success create user.",
            "code" => 201,
            "status" => true,
            "data" => $user
        ], 201);
    }

    public function login(Request $req) {
        $password = $req->json("password");
        $email = $req->json("email");

        $user = User::where("email", $email)->first();
        if($user && Hash::check($password, $user->password)) {
            $token = $user->createToken('token-name')->plainTextToken;
            return response([
                "status" => true,
                "data" => $user,
                "code" => 200,
                "message" => "Success login.",
                "token" => $token
            ]);
        }

        return response([
            "status" => false,
            "code" => 400,
            "message" => "Invalid login",
        ], 400);
    }

    public function test() {
        return response([
            "message" => "success"
        ]);
    }
}
