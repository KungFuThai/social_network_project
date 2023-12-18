<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\FriendController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ReactionController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TrashBinController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'fogetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);


Route::group([
    'middleware' => [
        'api',
        'auth:sanctum'
    ],
], function () {
    Route::get('get-auth', [AuthController::class, 'getAuth']);
    Route::post('logout', [AuthController::class, 'logout']);

    //friend controller
    Route::get('friends', [FriendController::class, 'getFriends']);
    Route::get('friend-request', [FriendController::class, 'friendRequests']);
    Route::get('suggest-friends', [FriendController::class, 'suggestFriends']);
    Route::post('send-request-to/{user}', [FriendController::class, 'sendFriendRequest']);
    Route::post('reject/{user}', [FriendController::class, 'reject']);
    Route::post('accept/{user}', [FriendController::class, 'accept']);
    Route::post('remove/{user}', [FriendController::class, 'remove']);

    Route::group([
        'prefix' => 'posts'
    ], function () {
        Route::post('/add', [PostController::class, 'store']);
        Route::get('/{post}/edit', [PostController::class, 'edit']);
        Route::post('/{post}/edit', [PostController::class, 'update']);
        Route::get('/{post}/show', [PostController::class, 'show']);
        Route::delete('/{post}', [PostController::class, 'destroy']);
        Route::post('update-status/{post}', [PostController::class, 'updatePostStatus']);
    });

    Route::group([
        'prefix' => 'comments'
    ], function () {
        Route::get('/{post}', [CommentController::class, 'index']);
        Route::post('/{post}/add', [CommentController::class, 'store']);
        Route::get('/{comment}/edit', [CommentController::class, 'edit']);
        Route::post('/{comment}/edit', [CommentController::class, 'update']);
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
    });

    //reaction controller
    Route::post('/reaction', [ReactionController::class, 'toggleReaction']);

    Route::group([
        'prefix' => 'messages'
    ], function () {
        Route::post('/{receiver_id?}', [MessageController::class, 'index']);
        Route::post('/store/{receiver_id}', [MessageController::class, 'store']);
        Route::post('/current_receiver/{user}', [MessageController::class, 'getCurrentReceiver']);
    });

    //home controller
    Route::get('/search', [HomeController::class, 'search']);
    Route::get('/posts', [HomeController::class, 'getPosts']);
    //profile
    Route::group([
        'prefix' => 'profile'
    ], function(){
        Route::get('/{user}', [ProfileController::class, 'index']);
        Route::post('/user/update', [ProfileController::class, 'updateProfile']);
    });

    Route::group([
        'prefix' => 'trash-bin'
    ], function(){
        Route::get('/', [TrashBinController::class, 'index']);
        Route::post('restore/{id}', [TrashBinController::class, 'restore']);
        Route::delete('/{id}', [TrashBinController::class, 'destroy']);
    });
});