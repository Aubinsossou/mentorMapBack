<?php

use App\Http\Controllers\DemandeMentorController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\DomaineMentorController;
use App\Http\Controllers\LangagesController;
use App\Http\Controllers\LangagesMentorController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MentoreeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('/v1/domaine/store', [DomaineController::class, 'store']);
Route::get('/v1/domaine/index', [DomaineController::class, 'index']);

Route::middleware("auth:mentor_api")->prefix("/v1/mentor")->group(function () {
    Route::get("/getMentor", [MentorController::class, 'getMentor']);
    Route::delete("/logout", [MentorController::class, 'logout']);
    Route::put("/update", [MentorController::class, 'update']);
    Route::get("/getdemandementor/{id}", [MentorController::class, 'getDemandeMentor']);
    Route::put("/acceptedemande/{id}", [DemandeMentorController::class, 'accepteDemande']);
    Route::put("/acceptedemande/{id}", [DemandeMentorController::class, 'accepteDemande']);
    Route::put("/refuseredemande/{id}", [DemandeMentorController::class, 'refuserDemande']);
    Route::post("/store/domainelangage", [DomaineMentorController::class, 'domaineMentor']);
});
Route::prefix("/v1/mentor")->controller(MentorController::class)->group(function () {
    Route::get("/listmentor", "index");
    Route::post("/register", "register");
    Route::post("/login", "login");
    Route::get("/showmentor/{id}", "showMentor");

});

Route::prefix("/v1/langage")->controller(LangagesController::class)->group(function () {
    Route::get("/index", "index");
    Route::post("/store", "store");
});

Route::prefix("/v1/langage/mentor")->controller(LangagesMentorController::class)->group(function () {
    Route::get("/index", "index");
    Route::post("/store", "store");
});



Route::middleware("auth:mentoree_api")->prefix("/v1/mentoree")->controller(MentoreeController::class)->group(function () {
    Route::get("/getmentoree","getMentoree");
    Route::get("/associatementor/{mentorId}","associatementor");
    Route::get("/mentorsAssocie","mentorsAssocie");
    Route::delete("/logout", [MentorController::class, 'logout']);

});

Route::middleware("auth:mentoree_api")->prefix("/v1/mentoree/")->controller(DemandeMentorController::class)->group(function () {
    Route::post("/demandementor","demandeMentor");
});
Route::prefix("/v1/mentoree")->controller(MentoreeController::class)->group(function () {
    Route::post("/register", "register");
    Route::post("/login", "login");
});

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'version' => 'Laravel 12'
    ]);
});


Route::get('/db-test', function () {
    DB::connection()->getPdo();
    return ['db' => 'connected'];
});

Route::options('/{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');


/* Route::middleware("auth:api")->prefix("/v1")->controller(UserController::class)->group(function () {
    Route::delete("/admin/logout", "logout");
});
 */
/* Route::prefix("/v1/admin")->controller(UserController::class)->group(function () {
    Route::post("/register", "register");
    Route::post("/login", "login");
}); */
