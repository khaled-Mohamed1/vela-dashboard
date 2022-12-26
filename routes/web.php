<?php

use App\Http\Controllers\MessengerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/',function (){
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin/dashboard',function (){
        return view('dashboard/index');
    })->name('index');

    Route::get('/admin/dashboard/reports',function (){
        return view('dashboard/src/reports');
    })->name('reports');


//users
    Route::get('/admin/dashboard/users',[EmployeeController::class, 'index'])->name('users');

    Route::get('/admin/dashboard/users/create',[EmployeeController::class, 'create'])->name('users.create');
    Route::post('/admin/dashboard/users/store',[EmployeeController::class, 'store'])->name('users.store');
    Route::get('/admin/dashboard/users/edit/{user}',[EmployeeController::class, 'edit'])->name('users.edit');
    Route::get('/admin/dashboard/users/profile/{user}',[EmployeeController::class, 'show'])->name('users.show');
    Route::post('/admin/dashboard/users/update/{user}',[EmployeeController::class, 'update'])->name('users.update');
    Route::delete('/admin/dashboard/users/delete/{user}',[EmployeeController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/dashboard/users_to/create',[EmployeeController::class, 'createTo'])->name('users.createTo');
    Route::post('/admin/dashboard/users_to/store',[EmployeeController::class, 'storeTo'])->name('users.storeTo');


//admin
    Route::get('/admin/dashboard/admins',[UserController::class, 'index'])->name('admins');

    Route::get('/admin/dashboard/admins/create',[UserController::class, 'create'])->name('admins.create');
    Route::post('/admin/dashboard/admins/store',[UserController::class, 'store'])->name('admins.store');
    Route::get('/admin/dashboard/admins/edit/{user}',[UserController::class, 'edit'])->name('admins.edit');
    Route::get('/admin/dashboard/admins/profile/{user}',[UserController::class, 'show'])->name('admins.show');

    Route::post('/admin/dashboard/admins/update/{user}',[UserController::class, 'update'])->name('admins.update');
    Route::delete('/admin/dashboard/admins/delete/{user}',[UserController::class, 'destroy'])->name('admins.destroy');



//tasks
    Route::get('/admin/dashboard/tasks',[TaskController::class, 'index'])->name('tasks');

    Route::get('/admin/dashboard/tasks/create',[TaskController::class, 'create'])->name('tasks.create');
    Route::post('/admin/dashboard/tasks/store',[TaskController::class, 'store'])->name('tasks.store');

    Route::get('/admin/dashboard/tasks/edit/{task}',[TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/admin/dashboard/tasks/update/{task}',[TaskController::class, 'update'])->name('tasks.update');

    Route::get('/admin/dashboard/tasks/profile/{task}',[TaskController::class, 'show'])->name('tasks.show');
    Route::post('/admin/dashboard/tasks/close/{task}',[TaskController::class, 'closed'])->name('tasks.closed');
    Route::post('/admin/dashboard/tasks/add_note/{task}',[TaskController::class, 'addNote'])->name('tasks.addNote');


    Route::delete('/admin/dashboard/tasks/delete/{task}',[TaskController::class, 'destroy'])->name('tasks.destroy');


    Route::get('/admin/dashboard/companies',function (){
        return view('dashboard/src/companies');
    })->name('companies');
});

//dashboard super admin
//Route::get('/admin/dashboard',function (){
//    return view('dashboard/index');
//})->name('index');
//
//Route::get('/admin/dashboard/reports',function (){
//    return view('dashboard/src/reports');
//})->name('reports');
//
//
////users
//Route::get('/admin/dashboard/users',function (){
//    return view('dashboard/src/users');
//})->name('users');
//
//Route::get('/admin/dashboard/users/create',function (){
//    return view('dashboard/src/add-user');
//})->name('users.create');
//
//Route::get('/admin/dashboard/users/edit',function (){
//    return view('dashboard/src/edit-user');
//})->name('users.edit');
//
//
////admin
//Route::get('/admin/dashboard/admins',function (){
//    return view('dashboard/src/admins');
//})->name('admins');
//
//Route::get('/admin/dashboard/admins/create',function (){
//    return view('dashboard/src/add-admin');
//})->name('admins.create');
//
//Route::get('/admin/dashboard/admins/edit',function (){
//    return view('dashboard/src/edit-admin');
//})->name('admins.edit');
//
//
//Route::get('/admin/dashboard/companies',function (){
//    return view('dashboard/src/companies');
//})->name('companies');









////////
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

//
//Route::get('/{id?}', [MessengerController::class, 'index'])
//    ->middleware('auth')
//    ->name('messenger');

