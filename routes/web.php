<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentCouncilController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FacultyCouncilController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if(auth()->user() && auth()->user()->is_active == 1)
        return view('home');
    else
        return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/deactivateUser/{user_id}', 'deactivateUser')->name('users.deactivate');
    Route::get('/registerRequests', 'registerRequests')->name('users.registerRequests');
    Route::post('/registerRequestDecision/{user_id}', 'registerRequestDecision')->name('users.registerRequestDecision');
});
Route::resource('users', UserController::class);


Route::prefix('faculties')->controller(FacultyController::class)->group(function () {
    Route::get('/get-faculties/{headquarterId}', 'getFacultiesByHeadquarter')->name('getFacultiesByHeadquarter');
});
Route::resource('faculties', FacultyController::class);


Route::resource('headquarters', HeadquarterController::class);

Route::prefix('departments')->controller(DepartmentController::class)->group(function () {
    Route::get('/get-departments/{facultyId}', 'getDepartmentsByFaculty')->name('getDepartmentsByFaculty');
    Route::get('/create/{faculty_id?}', [DepartmentController::class, 'create'])->name('departments.create');
});
Route::resource('departments', DepartmentController::class);


Route::prefix('faculty-councils')->controller(FacultyCouncilController::class)->group(function () {
    Route::get('/form/{faculty_id}', 'getForm')->name('facultyCouncil.getForm');
    Route::post('/formate/{faculty_id}', 'formateCouncil')->name('facultyCouncil.formate');
});


Route::prefix('department-councils')->controller(DepartmentCouncilController::class)->group(function () {
    Route::get('/form/{department_id}', 'getForm')->name('departmentCouncil.getForm');
    Route::post('/formate/{department_id}', 'formateCouncil')->name('departmentCouncil.formate');
});


Route::resource('topics', TopicController::class);
