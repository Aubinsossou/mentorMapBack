<?php

namespace App\Http\Controllers;

use App\Models\DomaineMentor;
use App\Models\Langages_mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomaineMentorController extends Controller
{
  public function domaineMentor(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "listDomaineMentor" => "required|array",
      "listDomaineMentor.*.mentor_id" => "required|numeric|exists:mentors,id",
      "listDomaineMentor.*.domaine_id" => "required|numeric|exists:domaines,id",
      "listLangageMentor" => "required|array",
      "listLangageMentor.*.mentor_id" => "required|numeric|exists:mentors,id",
      "listLangageMentor.*.langage_id" => "required|numeric|exists:langages,id",
    ]);
    if ($validator->fails()) {
      return response()->json([
        "status" => "Echec",
        "message" => "Validation echouer",
        "data" => $validator->errors(),
      ]);
    }


    foreach ($request->listDomaineMentor as $value) {
      $domaineMentor = DomaineMentor::create([
        "mentor_id" => $value["mentor_id"],
        "domaine_id" => $value["domaine_id"],
      ]);
    }

    foreach ($request->listLangageMentor as $value) {
      $langageMentor = Langages_mentor::create([
        "mentors_id" => $value["mentor_id"],
        "langages_id" => $value["langage_id"],
      ]);
    }


    return response()->json([
      "status" => "success",
      "message" => "domaine mentor creer ",
      "domaineMentor" => $domaineMentor,
      "langageMentor" => $langageMentor,
    ]);
  }
}
