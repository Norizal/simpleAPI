<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use App\User;

class UserController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error'=> true, 'error_message' => $validator->errors()], 401);
        }else{
            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');

            $dataEmail = User::where('email', $email)->count();

            if($dataEmail > 0){
                return response()->json(['error'=> true, 'error_message' => "Email sudah ada!"], 401);
            }else{
                $dataUser = new User;
                $dataUser->name = $name;
                $dataUser->email = $email;
                $dataUser->password = bcrypt($password);
                if($dataUser->save()){
                    $success['name'] = $dataUser->name;
                    $success['email'] = $dataUser->email;
                    return response()->json(['error'=>false, "message"=> $success], 200);

                }else{
                    return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);
                }        
            }

           

           

        }

        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);

        
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error'=> true, 'error_message' => $validator->errors()], 401);
        }else{
            $email = $request->input('email');
            $password = $request->input('password');

            $dataEmail = User::where('email', $email)->count();

            if($dataEmail < 1){
                return response()->json(['error'=> true, 'error_message' => "Anda belum mendaftar. Sila daftar dahulu!"], 401);
            }else{
                if(Auth::attempt(['email'=> $email, 'password'=> $password])){
                    $user = Auth::user();

                    $token = $user->createToken('MyApp')->accessToken;
                    // $success['id'] = $user->id;
                    // $success['name'] = $user->name;
                    // $success['email'] = $user->email;

                    return response()->json(['error' => false, 'message' => "Log masuk berjaya", 'token' => $token], 200);

                }else{
                    return response()->json(['error'=> true, 'error_message' => "Maklumat salah!"], 401);

                }
            }

        }

        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);

    }
}


