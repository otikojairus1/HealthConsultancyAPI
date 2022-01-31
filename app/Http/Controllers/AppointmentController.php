<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function add(Request $request){
        $rules = ['patient' => 'required', 'doctor'=>'required', 'time'=>'required', 'confirm'=>'required'];
        $input     = $request->only('patient','doctor', 'time', 'confirm');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $data = Appointment::create([
            'patient'=>$request->patient,
            'doctor'=>$request->doctor,
            'time'=>$request->time,
            'confirm'=>$request->confirm
        ]);
        return response()->json(['success' => true, 'response' => $data]);

    }

    public function approve($id){
        $data = Appointment::find($id);
        if(!$data){
            return response()->json(['success' => false, 'error' => "an error occured"]);
        }

        $data->confirm = true;
        $data->save();

        //sending an inbox message to the patient

        Message::create([
            'sender'=>$data->doctor,
            'receiver'=>$data->patient,
            'message'=>"Hello there ".$data->patient." . I am Dr. ". $data->doctor .", I just wanted to inform you that, I saw your consultation request and am glad to inform you that, it is accepted. Kindly keep time! Regards"
        ]);
        return response()->json(['success' => true, 'response' => "appointment confirmed successfully"]);
    }

    public function confirmedAppointments(Request $request){
        $rules = ['doctorEmail' => 'required'];
        $input     = $request->only('doctorEmail');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $data = Appointment::where(['doctor'=> $request->doctorEmail, 'confirm'=>true])->get();
        return response()->json(['success' => true, 'response' => $data]);


    }

    public function booked(Request $request){

        $rules = ['doctorEmail' => 'required'];
        $input     = $request->only('doctorEmail');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $data = Appointment::where(['doctor'=>$request->doctorEmail, 'confirm'=>false ])->get();
        return response()->json(['success' => true, 'response' => $data]);
    }

    public function pendingApproval(Request $request){
        $rules = ['email' => 'required'];
        $input     = $request->only('email');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $data = Appointment::where(['patient'=>$request->email, 'confirm'=>false ])->get();
        return response()->json(['success' => true, 'response' => $data]);
    }

    public function ApprovedPatients(Request $request){
        $rules = ['email' => 'required'];
        $input     = $request->only('email');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $data = Appointment::where(['patient'=>$request->email, 'confirm'=>true ])->get();
        return response()->json(['success' => true, 'response' => $data]);
    }
}
