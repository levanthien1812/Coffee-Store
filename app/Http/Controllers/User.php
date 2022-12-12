<?php

namespace App\Http\Controllers;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\User as UserModel;
use App\Models\Address;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
class User extends Controller
{
    //sign up
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
                
                $token=$user->createToken('myapptoken')->plainTextToken;

                //create and save address
                $address  =Address::create([
                    'UserID'=>$user->id,
                    'Value' => $fields['Address'],
                ]);

                $data= [
                    'userInfo' =>$user,
                    'userAddress'=>$address,
                ];

                return response( [
                    'data' => $data,
                ], 201);
            }
        }catch (Throwable $e) {
            return response([
                'message' => $e->getMessage()
            ], 401);
        }
       
        
    }

    //post login
    function userPostLogin(Request $request){
        try{
            $fields = $request->validate([
                "Username" =>"required|string",
                "Password" =>"required|string",
            ]);
           
            //check username and password
            $user = UserModel::where('Username', $fields['Username'])->first();

            if(!$user || !Hash::check($fields['Password'], $user->Password)){
                return response([
                    'message' => 'Username hoặc Password không đúng',
                ], 401);
            }

            //create token
            $token=$user->createToken('accessToken')->plainTextToken;

            //find address
            $address=Address::where('UserID', $user->id)->get();

            $userInfo = 
            $response = [
                'message' =>'Đăng nhập thành công',
                'accessToken' => $token,
                'userInfo' => [
                    'user' => $user,
                    'address' => $address,
                ]
               
            ];

            return response($response);

        }catch (Throwable $e){
             return response([
                'message' => $e->getMessage()
            ]);
        }
    }

    //logout 
    function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'log out'
        ];
    }

    //login with get method
    function userGetLogin(Request $request){
        $user = auth('sanctum')->user();
        return response([
            "userInfo", $user
        ]);
    }
}
