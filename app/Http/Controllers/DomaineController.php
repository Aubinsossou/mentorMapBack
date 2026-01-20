<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Langages;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class DomaineController extends Controller
{
    public function index (){
        $domaine = Domaine::all();
        $langages = Langages::all();

      
            return response()->json([
                "status" => "Success",
                "message" => "Domaine récuperer avec success",
                "data" => $domaine,
                "langages" => $langages,
            ]);
        
    }
     public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
                'message' => 'Validation échoué',
            ], 400);
        }


       /*  Role::create(["name"=>"mentor","guard" => "mentor_api"]);
        Role::create(["name"=>"mentoree","guard" => "mentoree_api"]); */

        $domaine = Domaine::create([
            'name' => $request->name,  
        ]);


        return response()->json([
            'message' => 'Domaine créé avec success',
            'data' => $domaine,
        ]);
    }
}
