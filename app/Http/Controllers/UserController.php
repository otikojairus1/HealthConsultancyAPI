<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Suggestion;
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
            'password'=>'required'
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
            'account' => 'required',
            'password' => 'required',
        ];

        $input     = $request->only('name','email', 'account','password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = new User();
        $user->email = $request->email;
        $user->account = $request->account;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

       if( $user->save() ){
           return response()->json(['success' => true, 'message' => 'user has registered successfully.', "data"=>$user]);
       }else{
           return response()->json(['success' => false, 'error' => 'we encountered an error']);
       }

    }


    public function add_suggestion(Request $request){

        $rules = [
            'One' => 'required',
            'Two' => 'required',
            'Three' => 'required',
            'Four' => 'required',
            'Five' => 'required',
            'name' => 'required',
        ];

        $input     = $request->only('One','name','Two', 'Three','Four','Five');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = new Suggestion();
        $user->One = $request->One;
        $user->name = $request->name;
        $user->Two = $request->Two;
        $user->Three = $request->Three;
        $user->Four = $request->Four;
        $user->Five = $request->Five;
    


       if( $user->save() ){
           return response()->json(['success' => true, 'message' => 'suggestion added successfully.', "data"=>$user]);
       }else{
           return response()->json(['success' => false, 'error' => 'we encountered an error']);
       }

        return response()->json(['success' =>"100%"]);

    }


    public function get_suggestion(){
        $data = Suggestion::all();
        return response()->json(['success' => true, 'data' => $data]);

    }

    public function get_doctor(){
        $data = User::where('account','doctor')->get();
        return response()->json(['success' => true, 'data' => $data]);

    }
}
