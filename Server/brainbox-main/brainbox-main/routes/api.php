<?php

use App\Http\Controllers\Notes\GroupNote;
use App\Http\Controllers\Notes\TodoListGroupNoteController;
use App\Http\Controllers\Notes\TodoListNoteController;
use App\Http\Controllers\Notes\UserNoteController;
use App\Http\Controllers\SharedLink\GetLinkData;
use App\Http\Controllers\SharedLink\ShareData;
use App\Http\Controllers\Tasks\GroupsTodolistsTasks;
use App\Http\Controllers\TodoLists\GroupsTodoList;
use App\Http\Controllers\UpdareController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoLists\TodoListController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;




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
//Todolists
Route::middleware('auth:sanctum')->apiResource('users.todolists', TodoListController::class);
Route::middleware('auth:sanctum')->apiResource('users.todolists.tasks', TaskController::class);
Route::middleware('auth:sanctum')->apiResource('users.todolists.notes', TodoListNoteController::class);

 
//notes
Route::middleware('auth:sanctum')->apiResource('users.notes', UserNoteController::class);


//groups
Route::middleware('auth:sanctum')->apiResource('users.groups', GroupController::class);
Route::middleware('auth:sanctum')->apiResource('users.groups.notes',GroupNote::class);
Route::middleware('auth:sanctum')->apiResource('users.groups.todolists',GroupsTodoList::class);
Route::middleware('auth:sanctum')->apiResource('users.groups.todolists.notes',TodoListGroupNoteController::class);
Route::middleware('auth:sanctum')->apiResource('users.groups.todolists.tasks', GroupsTodolistsTasks::class);



//admin rechte für groupen 
Route::middleware('auth:sanctum')->post('users/{userId}/groups/{groupId}/add-users', [GroupController::class, 'addMember']);
Route::middleware('auth:sanctum')->delete('users/{userId}/groups/{groupId}/remove_user', [GroupController::class, 'removeMember']);
Route::middleware('auth:sanctum')->post('users/{userId}/groups/{groupId}/makeAdmin', [GroupController::class, 'makeAdmin']);

//Share Links Note
Route::middleware('auth:sanctum')->post('users/{userId}/notes/{noteId}/share', [ShareData::class, 'makeNoteLink']);
Route::middleware('auth:sanctum')->post('users/{userId}/groups/{groupId}/notes/{noteId}/share', [ShareData::class, 'makeNoteGroupLink']);

//Share Links Todolist
Route::middleware('auth:sanctum')->post('users/{userId}/todolists/{todolistId}/share', [ShareData::class, 'makeTodoLink']);
Route::middleware('auth:sanctum')->post('users/{userId}/groups/{groupId}/todolists/{todolistId}/share', [ShareData::class, 'makeTodoGroupLink']);


//Get Data From Link
Route::middleware('auth:sanctum')->get('{hashedId}', [GetLinkData::class, 'findLinkData']);


// project Admin 
Route::middleware('auth:sanctum')->get('admin/{adminId}/getusers', [AdminController::class, 'getUsers']);
Route::middleware('auth:sanctum')->get('admin/{adminId}/{email}/search', [AdminController::class, 'search']);
Route::middleware('auth:sanctum')->post('admin/{adminId}/{email}/block', [AdminController::class, 'blockUser']);
Route::middleware('auth:sanctum')->post('admin/{adminId}/{email}/unblock', [AdminController::class, 'unblockUser']);

//Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//updare Users Informations

Route::middleware('auth:sanctum')->post('name/{user}/update', [AuthController::class, 'updateName']);
Route::middleware('auth:sanctum')->post('password/{user}/update', [AuthController::class, 'updatePassword']);




