<?php

namespace App\Http\Controllers;

use App\Models\demandeMentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DemandeMentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function demandeMentor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "mentors_id" => "required|numeric|exists:mentors,id",
            "mentoree_id" => "required|numeric|exists:mentorees,id",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => "Echec",
                "message" => "Validation echouer",
                "data" => $validator->fails(),
            ]);
        }

        $demandeMentor = demandeMentor::create([
            "mentors_id" => $request->mentors_id,
            "mentoree_id" => $request->mentoree_id,
            "status" => "false",
        ]);

        return response()->json([
            "status" => "success",
            "message" => "domaine mentor creer ",
            "data" => $demandeMentor,
        ]);
    }

    public function accepteDemande(Request $request, $id)
    {

        $demandeMentoreeaccepter = demandeMentor::find($id);

        if ($demandeMentoreeaccepter) {
            $demandeMentoreeaccepter->update([
                "status" => "true"
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Demande de mentoree accepter accepter",
                "data" => $demandeMentoreeaccepter,
            ]);
        }
        

    }

     public function refuserDemande(Request $request, $id)
    {

        $demandeMentoreeRefuser = demandeMentor::find($id);

        if ($demandeMentoreeRefuser) {
            $demandeMentoreeRefuser->update([
                "status" => "refuser"
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Demande de mentoree accepter accepter",
                "data" => $demandeMentoreeRefuser,
            ]);
        }
    

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(demandeMentor $demandeMentor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, demandeMentor $demandeMentor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(demandeMentor $demandeMentor)
    {
        //
    }
}
