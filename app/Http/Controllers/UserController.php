<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //login logic
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $input     = $request->only('email','password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' =>  $request->password])){
            $user = Auth::user();
            return response()->json(['success'=>true,'userDetails'=>$user ], 200);
        }
        else{
            return response()->json(['success'=>false,'error'=>'wrong login credentials' ], 200);
        }

    }

    //sign up logic

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'unique:users|required|email',
            'password' => 'required',
        ];

        $input     = $request->only('name','email','password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

       if( $user->save() ){
           return response()->json(['success' => true, 'message' => 'user has registered successfully.', "data"=>$user]);
       }else{
           return response()->json(['success' => false, 'error' => 'we encountered an error']);
       }

    }
}
