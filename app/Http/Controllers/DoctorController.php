<?php

namespace App\Http\Controllers;
Use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Comment\Doc;

class DoctorController extends Controller
{
    public function add(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'regno' => 'required',
            'availability' => 'required',
        ];

        $input     = $request->only('email','password', 'name','regno', 'availability');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $data = Doctor::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'regno'=>$request->regno,
            'password'=>$request->password,
            'availability'=>$request->availability,
        ]);

        return response()->json(['success' =>$data]);

    }

    public function login(Request $request){
        $rules = [

            'email' => 'required',
            'password' => 'required',

        ];

        $input     = $request->only('email','password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $doctor = Doctor::where(['email'=> $request->email, 'password'=>$request->password])->first();
        if($doctor){
            return response()->json(['success' => true, 'response' => $doctor]);
        }else{
            return response()->json(['success' => false, 'error' => "wrong credentials"]);
        }




    }

    public function online(Request $request){
        $rules = ['availability' => 'required', 'email'=>'required'];
        $input     = $request->only('availability','email');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $doctor = Doctor::where('email', $request->email)->first();

        if(!$doctor){
            return response()->json(['success' => false, 'error' => "an error occured"]);
        }
        $doctor->availability = $request->availability;
        $doctor->save();
        return response()->json(['success' => true, 'response' => "user availability updated"]);


    }

    public function listdoctors(){
        $data = Doctor::all();
        return response()->json(['success' => true, 'response' => $data]);
    }

    public function availableDoctors(){
        $data = Doctor::where('availability',true)->get();
        return response()->json(['success' => true, 'response' => $data]);
    }


}
