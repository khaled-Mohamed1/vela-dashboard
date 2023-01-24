<?php

use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MessagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
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

//Route::middleware('auth:sanctum')->group(function() {

Route::get('conversations', [ConversationsController::class, 'index']);
Route::get('conversations/{conversation}', [ConversationsController::class, 'show']);
Route::post('conversations/{conversation}/participants', [ConversationsController::class, 'addParticipant']);
Route::delete('conversations/{conversation}/participants', [ConversationsController::class, 'removeParticipant']);

Route::put('conversations/{conversation}/read', [ConversationsController::class, 'markAsRead']);


Route::get('conversations/{id}/messages', [MessagesController::class, 'index']);
Route::post('messages', [MessagesController::class, 'store'])
    ->name('api.messages.store');
Route::delete('messages/{id}', [MessagesController::class, 'destroy']);
//});

Route::post('user/verify',[AuthController::class, 'cube_register']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login_company',[AuthController::class, 'loginCompany']);
    Route::post('login',[AuthController::class, 'login']);
    Route::get('users',[AuthController::class, 'getUsers']);

//    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::post('update_info', [UserController::class, 'updateInfo']);


        //contacts
        Route::post('contacts', [UserController::class, 'contacts']);

        //task
        Route::post('task_get', [UserController::class, 'taskGet']);
        Route::post('task_get_user', [UserController::class, 'taskGetUser']);
        Route::post('task_get_note', [UserController::class, 'taskGetNote']);
        Route::post('task_store_note_user', [UserController::class, 'taskStoreNoteUser']);
        Route::post('task_update_user', [UserController::class, 'taskUpdateUser']);
//        Route::post('task_update', [UserController::class, 'taskUpdate']);

        //todolist
        Route::post('todolist', [UserController::class, 'todolistGet']);
        Route::post('todolist/store', [UserController::class, 'todolistStore']);
        Route::post('todolist/status', [UserController::class, 'todolistUpdate']);

        //event
        Route::post('events', [UserController::class, 'events']);
        Route::post('event/store', [UserController::class, 'eventStore']);
        Route::post('event/status', [UserController::class, 'eventUpdate']);
        Route::post('event/delete', [UserController::class, 'eventDelete']);


    });

});

