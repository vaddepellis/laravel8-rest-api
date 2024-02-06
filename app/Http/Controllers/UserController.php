<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return response()->json(['message'=>'welcome to api home']);
    }
    public function login(Request $request){
        $validate = $request->validate([
            'email'=>'required:min:6',
            'password'=>'required:min:8'
        ],[
            'email'=>[
                'required'=>'username is required',
                'min'=>'username not less than 6 characters'
            ],
            'password'=>[
                'required'=>'password is required',
                'min'=>'password must be 8 or more characters'
            ]
        ]);
        try{
            $data = User::where('email',$request->email)->first();
            if(@$data){
                $verify = Hash::check($request->password,$data->password);
                if(!$verify){
                    return response()->json(['errors'=>'password is incorrect']); 
                }
                
            }
            return response()->json(['message'=>'login success']); 
        }
        catch(Exception $e){
            return respose()->json(['errors'=>$e->getMessage()]);
        }
    }
    public function register(Request $request){
        try{
            $email = User::where('email',$request->email)->first();
            if(@$email){ 
                return response()->json(['errors'=>'email exits']);
            }
        }
        catch(Exception $e){
            return response()->json(['errors'=>$e->getMessage()]);
        }
        
        $validate = $request->validate([
            'username'=>'required|min:6',
            'email'=>'required|max:100',
            'password'=>'required|min:8',
            'mobile'=>'required|min:10|max:13'
        ],[
            'username'=>[
                'required'=>'username is required',
                'min'=>'username should be 6 or more characters'
            ],
            'email'=>[
                'required'=>'email id is required',
                'max'=>'email should be less than 100 characters',
                
            ],
            'password'=>[
                'required'=>'password is required',
                'min'=>'password should be 8 or more characters'
            ],
            'mobile'=>[
                'required'=>'mobile number is required',
                'min'=>'enter valida mobile number',
                'max'=>'enter valida mobile number'
            ]
        ]);
        try{
            if(@$validate->errors){
                throw new Exception("validation error");
            }
            $validate['password'] = Hash::make($request->password);
            $user = User::create($validate);
            if($user){
                return response()->json(['message'=>'user created']);
            }
           else{
            throw new Exception("error occured");
           }
        }
        catch(Exception $e){
            return response()->json(['errors'=>$e->getMessage()]);
        }
            
    }
}
