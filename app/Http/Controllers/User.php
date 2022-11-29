<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class User extends Controller
{
    //
    function userSignup (Request $request){
        try{
             $fields = $request->validate([
                "Fullname" =>"required|string",
                "Username" =>"required|string",
                "Password" =>"required|string",
                "PhoneNumber"=>"required|numeric|digits:10",
                "Address"=>"required|string"
            ]);

            //check username
            $user = UserModel::where("Username", $fields["Username"])->first();

            if($user){
                return response(
                    [
                        'message' => 'tên người dùng(username) đã tồn tại',
                    ], 401
                );
            }else{
                //create new user
                $user = UserModel::create([
                    'Fullname' => $fields['Fullname'],
                    'Username' => $fields['Username'],
                    'Password' => bcrypt($fields['Password']),
                    'PhoneNumber'=>$fields['PhoneNumber'],
                ]);

                //create token
                $token=$user->createToken('accessToken')->plainTextToken;

                $response = [
                    'user' => $user,
                    'token' => $token,
                ];

                return response( $response, 201);
            }
        }catch (Throwable $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
       
        
    }
}
