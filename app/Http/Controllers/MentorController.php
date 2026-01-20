<?php

namespace App\Http\Controllers;

use App\Models\demandeMentor;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Validator;

class MentorController extends Controller
{


    public function index()
    {
        $mentors = Mentor::with('domaines')->get();

        if ($mentors) {

            return response()->json([
                'status' => 'success',
                'message' => 'Liste de mentors',
                'data' => $mentors,
            ]);
        }
        return response()->json([
            'status' => 'Echec',
            'message' => 'Liste de mentors non trouver',
            'data' => $mentors,
        ]);

    }
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string',
            'ville' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'experience_annees' => 'required|integer',
            'password' => 'required|string|min:4',
            'bio' => 'required|string',
            'tarif_horaire' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
                'message' => 'Validation échoué',
            ], 400);
        }
        $mentor = Mentor::create([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'ville' => $request->ville,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'experience_annees' => $request->experience_annees,
            'tarif_horaire' => $request->tarif_horaire,
            'bio' => $request->bio,
            'password' => Hash::make($request->password),
        ]);
        $mentor->assignRole("mentor");


        return response()->json([
            'status' => 'success',
            'message' => 'Mentor créé avec succès',
            'data' => $mentor,
        ]);
    }
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
                'message' => 'Connexion failed',
            ], 400);
        }

        $mentor = Mentor::where('email', $request->email)->get()->first();
        $mentor->getRoleNames();


        if ($mentor && Hash::check($request->password, $mentor->password)) {
            Auth::login($mentor);
            $accessToken = $mentor->createToken('MentorToken')->accessToken;
            $refreshToken = $mentor->createToken('refreshMentorToken')->accessToken;

            return response()->json([
                "status" => 'success',
                'message' => 'Connexion réussi',
                'data' => $mentor,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,

            ]);
        }
        if (!$mentor) {
            return response()->json([
                'message' => 'Aucun utilisateur trouver avec ce mail',
            ], 400);
        }
        return response()->json([
            'message' => 'Email ou mot de passe incorrect',
        ], 400);
    }
    public function update(Request $request)
    {
        $mentor = Auth::guard('mentor_api')->user();

        if (!$mentor) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mentors,email,' . $mentor->id,
            'telephone' => 'required|string',
            'ville' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'experience_annees' => 'required|integer',
            'bio' => 'required|string',
            'tarif_horaire' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Echec',
                'message' => 'Validation échoué',
                'errors' => $validator->errors(),
            ], 400);
        }

        $mentor->update($request->only([
            'name',
            'email',
            'telephone',
            'ville',
            'latitude',
            'longitude',
            'experience_annees',
            'tarif_horaire',
            'bio',
        ]));

        return response()->json([
            "status" => 'success',
            'message' => 'Mentor mis à jour',
            'data' => $mentor->fresh(),
        ]);
    }

    public function showMentor($id)
    {
        $mentorShow = Mentor::find($id)->makeHidden(['password'])->load(['domaines', 'langages']);

        
        $mentorShow->getRoleNames();

        $numberMentoree = $mentorShow->demande_mentors()->where('status',  'true')->get();


        return response()->json([
            "status" => "success",
            "message" => "Mentor retrouver avec success",
            "data" => $mentorShow,
            "numberMentoree" => count($numberMentoree),
        ]);
    }

    public function getMentor()
    {


        $mentors = Auth::guard('mentor_api')->user()->makeHidden(['password'])->load(['domaines', 'langages']);
        ;

        $numberMentoree = $mentors->demande_mentors()->where('status', "true")->count();
        $mentors->getRoleNames();

        $demandeMentor = Mentor::with("demande_mentors")->get();

        if ($mentors) {
            return response()->json([
                "status" => "Success",
                "message" => " mentor trouver avec success",
                "data" => $mentors,
                "numberMentoree" => $numberMentoree,
                "demandeMentor" => $demandeMentor,


            ]);
        }
        return response()->json([
            "status" => "Echec",
            "message" => "Aucun mentor conncter n'a ete trouver",
        ]);
    }

    public function getDemandeMentor($id)
    {
        $getDemandeMentor = Mentor::find($id)
            ->demande_mentors()
            ->where('status', "false")
            ->with('mentoree')
            ->get();

        if ($getDemandeMentor) {
            return response()->json([
                "status" => "success",
                "message" => "Des demande de mentor vous concernant ont été retrouver",
                "data" => $getDemandeMentor,
            ]);
        }
    }



    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            "status" => "Success",
            "message" => "Logout is success"
        ]);
    }

}
