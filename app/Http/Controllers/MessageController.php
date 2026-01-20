<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "mentor_id" => "required|integer|exists:mentors,id",
            "mentoree_id" => "required|integer|exists:mentorees,id",
            "message" => "required|string|",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "Echec",
                "message" => "erreur lors de la creation du message",
                "errors" => $validator->errors(),
            ]);
        }
        $message = Message::create([
            "mentor_id" => $request->mentor_id,
            "mentoree_id" => $request->mentoree_id,
            "message" => $request->message,
        ]);

        return response()->json([
            "status" => "success",
            "message" => " creation du message avec success",
            "data" => $message,
        ]);
    }
}
