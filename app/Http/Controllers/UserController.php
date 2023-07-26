<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUserById($id) {
        $user = User::find($id);
        if(!$user) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => "Invalid User ID"
            ], 400);
        }

        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success get User",
            "data" => $user
        ], 200);
    }

    public function getUserByToken() {
        $user = auth("sanctum")->user();
        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success get User By Token",
            "data" => $user
        ]);
    }

    public function updateUser(Request $req) {
        $user = auth("sanctum")->user();

        $rules = [
            "name" => "min:3",
            "job" => "min:3",
            "description" => "min:3"
        ];

        $valid = Validator::make($req->all(), $rules);

        if($valid->fails()) {
            return response([
                "code" => 400,
                "status" => false,
                "message" => $valid->errors()->first()
            ], 400);
        }

        $validated = $valid->safe()->only(["name", "job", "description"]);
        
        $user->fill($validated);
        $user->save();

        return response([
            "code" => 200,
            "status" => true,
            "message" => "Success Update User",
            "data" => $user
        ], 200);

    }
}
