<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends BaseController{
    public function signup(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "reqired",
            "email"=> "reqired",
            "password"=> "reqired",
            "confirmed_password"=> "reqired|same:password"
        ]);

        if($validator->fails()){
            return $this->sendError("Validálási hiba", $validator->errors() );
        }
        $input = $request->all();
        $input["password"] = bcrycpt($input["password"]);
        $user = User::create($input);
        $success["name"] = $user->name;
        return $this->sendResponse($success, "Sikeres regisztráció");
    }
    public function signIn(Request $request){
        if(Auth::attempt(["email" => $request->email,"password" => $request->password])){
            $authUser = Auth::user();
            $success["token"] = $authUser->createToken("myapitoken")->plainTextToken;
            $success["name"] = $authUser->name;
            return $this->sendResponse($success, "Sikeres bejelentkezés");
        }
        else{
            return $this->sendError("Unauthorized", ['error' => "Hibás adatok"] );
        }
    }
}
