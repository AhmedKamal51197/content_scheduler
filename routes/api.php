<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('test-auth',function(){
        return response()->json(['hello world']);
    });
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('profile',[AuthController::class,'profile']);
    Route::put('profile',[AuthController::class,'updateProfile']);
    Route::delete('terminate-profile',[AuthController::class,'deleteProfile']);
    Route::get('users/platforms',[PlatformController::class,'userPlatforms']);
    Route::post('users/patforms/{platform}',[PlatformController::class,'userJoinPlatform']);
    Route::put('users/platforms/toggle-active',[PlatformController::class,'TogglePlatformsActive']);
    Route::post('posts',[PostController::class,'postPlatfromStore']);
    Route::get('posts',[PostController::class,'userPosts']);
    Route::put('posts/{post}/scheduled-time',[PostController::class,'updatePostScheduledTime']);
    Route::delete('posts/{post}',[PostController::class,'destroy']);

});
Route::get('platforms',[PlatformController::class,'index']);
Route::get('platforms/{platform}',[PlatformController::class,'show']);
Route::post('platforms',[PlatformController::class,'store']);
Route::put('platforms/{platform}',[PlatformController::class,'update']);
Route::delete('platforms/{platform}',[PlatformController::class,'destroy']);
