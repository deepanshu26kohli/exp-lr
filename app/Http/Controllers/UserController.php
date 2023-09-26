<?php

namespace App\Http\Controllers;

use App\Models\User ;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private function prepareResult($status, $data, $errors,$msg)
    {   
        return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
    }
    public function accessToken(Request $request){
       
        $validate = $this->validations($request,"login");
        
        if($validate["error"]){
            return $this->prepareResult(false, [], $validate['errors'],"Error while validating user");
        }
        $user = User::where("email",$request->email)->first();
       
       if($user){
           if (Hash::check($request->password,$user->password)) {
               return $this->prepareResult(true, ["accessToken" => $user->createToken('Users')], [],"User Verified");
           }else{
               return $this->prepareResult(false, [], ["password" => "Wrong passowrd"],"Password not matched");  
           }
       }else{
           return $this->prepareResult(false, [], ["email" => "Unable to find user"],"User not found");
       }
    }
    public function validations($request,$type){
        $errors = [];
        $error = false;
        if($type == "login"){
            // dd($request->all());
            $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
            ]);
            if($validator->fails()){
                
                $error =true;
                $errors =  $validator->errors();
            }
        }
        return ["error" => $error,"errors"=>$errors];
    }
}
