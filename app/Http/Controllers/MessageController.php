<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function add(Request $request){
        $rules = [
            'sender' => 'required',
            'receiver' => 'required',
            'message' => 'required',

        ];

        $input     = $request->only('sender','receiver', 'message');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $data = Message::create([
            'sender'=>$request->sender,
            'receiver'=>$request->receiver,
        'message'=>$request->message
        ]);

        return response()->json(['success' => true, 'response' => $data]);

    }

    public function inbox(Request $request){
        $rules = [
            'owner' => 'required',

        ];

        $input     = $request->only('owner');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $data = Message::where('receiver', $request->owner)->get();
        return response()->json(['success' => true, 'response' => $data]);

    }
}
