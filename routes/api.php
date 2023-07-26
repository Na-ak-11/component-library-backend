<?php

use App\Http\Controllers\API\ComponentController;
use App\Http\Controllers\UserInteractionController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;


use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AvatarController;

/*
|--------------------------------------------------------------------------
| API Routes API
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/homeComponent', [DashboardController::class, 'limitedComponents']);
//component route
Route::post('/add-component', [ComponentController::class, 'add']);
Route::get('/component', [ComponentController::class, 'getComponent']);
Route::get('/single-component/{id}', [ComponentController::class, 'singleComponent']);
Route::get('/singleCategory-component/{id}', [ComponentController::class, 'singleCategoryComponent']);
Route::get('/singleUser-component/{id}', [ComponentController::class, 'singleUserComponent']);
Route::get('/component/code/{id}', [ComponentController::class, 'getCode']);
Route::put('/update-component/{id}', [ComponentController::class, 'updateComponent']);
Route::put('/update-componentview/{id}', [ComponentController::class, 'updateComponentview']);
Route::post('/update-componentlike', [LikesController::class, 'create']);
Route::delete('/delete-component/{id}', [ComponentController::class, 'deleteComponent']);

Route::get('/userlike/{id}/components', [LikesController::class, 'getUserLikedComponent']);
//category route
Route::get('/category', [CategoryController::class, 'getCategory']);
Route::post('/add-category', [CategoryController::class, 'store']);
Route::get('/single-category/{id}', [CategoryController::class, 'singleCategory']);
Route::put('/update-category/{id}', [CategoryController::class, 'update']);
Route::delete('/delete-category/{id}', [CategoryController::class, 'delete']);
Route::get('/search/{searchQuery}', [CategoryController::class, 'searchCategory']);

Route::post( '/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/users', [UserController::class, 'users']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/user/profile/{id}', [UserController::class, 'singleUser']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    // Route::get('/components/all', [ComponentController::class, 'getComponent']);
    //update profile
    Route::get('/editprofile/{id}', [UserController::class, 'edit']);
    Route::post('/updateprofile/{id}', [UserController::class, 'update']);
});


/////avatar route
Route::get('/upload/{id}', [AvatarController::class, 'view']);
Route::post('/upload/{id}', [AvatarController::class, 'store']);

//user Interaction 
Route::post('/user/interaction', [UserInteractionController::class, 'store']);
Route::get('/singelUser/interaction/{id}', [UserInteractionController::class, 'singleUserInteraction']);
Route::get('/singelUser/recommendation/{id}', [UserInteractionController::class, 'singleUserRecommendation']);
/////image route
Route::post('/uploadImage/{id}', [ImageController::class, 'storeUserImage']);
Route::get('/userImage/{id}', [ImageController::class, 'getImage']);






///comment
 Route::post('/comments/add', [CommentController::class, 'store']);
 Route::get('/comments/{id}', [CommentController::class, 'index']);

 


