<?php

namespace App\Http\Controllers;

use App\Models\Langages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LangagesController extends Controller
{
     public function index()
    {
        $langages = Langages::class;

        if ($langages) {
            return response()->json([
                "status" => "success",
                "message" => "Liste de mangage retrouver avec success",
                "data" => $langages,
            ]);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "langage" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "Echec",
                "message" => $validator->errors(),
            ]);
        }

        $langages = Langages::create([
            "langage" => $request->langage,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "langages creer avec succes",
            "data" => $langages,
        ]);
    }
}
