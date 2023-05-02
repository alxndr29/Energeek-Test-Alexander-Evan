<?php

use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\SkillsController;
use App\Models\Candidates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('/jobs', JobsController::class);
Route::resource('/candidates', CandidatesController::class);
Route::resource('/skills', SkillsController::class);

Route::get('skills/show/{id}', [SkillsController::class, 'show']);
Route::post('skills/store/', [SkillsController::class, 'store']);
Route::put('skills/update/{id}', [SkillsController::class, 'update']);
Route::delete('skills/delete/{id}', [SkillsController::class, 'destroy']);

Route::get('jobs/show/{id}', [JobsController::class, 'show']);
Route::post('jobs/store/', [JobsController::class, 'store']);
Route::put('jobs/update/{id}', [JobsController::class, 'update']);
Route::delete('jobs/delete/{id}', [JobsController::class, 'destroy']);

Route::get('candidates/',[CandidatesController::class,'index']);
Route::post('candidates/store',[CandidatesController::class,'store']);
Route::get('candidates/show/{id}', [CandidatesController::class, 'show']);
Route::put('candidates/update/{id}', [CandidatesController::class, 'update']);
Route::delete('candidates/delete/{id}', [CandidatesController::class, 'destroy']);