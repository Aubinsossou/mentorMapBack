<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\Mentoree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class MentoreeController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'password' => 'required|string|min:4',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
                'message' => 'Validation échoué',
            ], 400);
        }
        $mentoree = Mentoree::create([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'password' => Hash::make($request->password),
        ]);

        $mentoree->assignRole("mentoree");

        return response()->json([
            'message' => 'Mentoree créé avec success',
            'data' => $mentoree,
        ]);
    }
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // dd($request->all());
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
                'message' => 'Connexion failed',
            ], 400);
        }
        $mentoree = Mentoree::where('email', $request->email)->get()->first();
        $mentoree->getRoleNames();


        if ($mentoree && Hash::check($request->password, $mentoree->password)) {
            Auth::login($mentoree);
            $accessToken = $mentoree->createToken('MentoreeToken')->accessToken;
            $refreshToken = $mentoree->createToken('refreshMentoreeToken')->accessToken;

            return response()->json([
                "sucesss" => true,
                'message' => 'Connexion réussi',
                'data' => $mentoree,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,

            ]);
        }
        if (!$mentoree) {
            return response()->json([
                'message' => 'Aucun utilisateur trouver avec ce mail',
            ], 400);
        }
        return response()->json([
            'message' => 'Email ou mot de passe incorrect',
        ], 400);
    }

    public function getMentoree()
    {
        $mentoree = Auth::guard('mentoree_api')->user()->makeHidden(["password"]);


        $mentoree->getRoleNames();


        if ($mentoree) {
            return response()->json([
                "status" => "Success",
                "message" => " mentoree trouver avec success",
                "data" => $mentoree,


            ]);
        }
        return response()->json([
            "status" => "Echec",
            "message" => "Aucun mentoree conncter n'a ete trouver",
        ]);
    }

    public function associateMentor($mentorId)
    {
        $mentor = Mentor::find($mentorId);

        $mentoree = Auth::guard('mentoree_api')->user()->makeHidden(["password"]);

        $dejaAssocied = $mentor->demande_mentors()->where('mentoree_id', $mentoree->id)->whereIn('status', ['false', 'true'])->exists();


        //dd($mentor);
        return response()->json([
            "status" => "success",
            'dejaAssocied' => $dejaAssocied,
        ]);
    }

    public function mentorsAssocie()
    {
        $mentoree = Auth::guard('mentoree_api')->user()->makeHidden(["password"]);


        $mentorsAssocies =  $mentoree->mentors()->with('domaines')->get();


        return response()->json([
            "status" => "success",
            //'dejaAssocied' => $dejaAssocied,
            "mentorsAssocies" => $mentorsAssocies,
        ]);
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
