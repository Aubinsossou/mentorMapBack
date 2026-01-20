<?php

namespace App\Http\Controllers;

use App\Models\Langages;
use App\Models\Langages_mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LangagesMentorController extends Controller
{
    public function index()
    {
        $langageMentor = Langages_mentor::class;

        if ($langageMentor) {
            return response()->json([
                "status" => "success",
                "message" => "Liste de mangage retrouver avec success",
                "data" => $langageMentor,
            ]);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "mentors_id" => "required|numeric|exists:mentors,id",
            "langages_id" => "required|numeric|exists:langages,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "Echec",
                "message" => $validator->errors(),
            ]);
        }

        $langagesMentor = Langages_mentor::create([
            "mentors_id" => $request->mentors_id,
            "langages_id" => $request->langages_id,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "LangageMentor creer avec succes",
            "data" => $langagesMentor,
        ]);
    }
}
