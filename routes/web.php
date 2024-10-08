<?php

use App\Http\Controllers\CollegeCouncilController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentCouncilController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FacultyCouncilController;
use App\Http\Controllers\FacultySessionController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\SessionDepartmentController;
use App\Http\Controllers\TopicAgendaController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (auth()->user() && auth()->user()->is_active == 1)
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
    Route::get('/get_faculties_by_headquarter_id/{headquarterId}', 'getFacultiesByHeadquarter')->name('getFacultiesByHeadquarter');
});
Route::resource('faculties', FacultyController::class);


Route::resource('headquarters', HeadquarterController::class);

Route::prefix('departments')->controller(DepartmentController::class)->group(function () {
    Route::get('/get-department/{departmentId}', 'getDepartment')->name('getDepartment');
    Route::get('/get_departments_by_faculty_id/{facultyId}', 'getDepartmentsByFaculty')->name('getDepartmentsByFaculty');
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


Route::prefix('topics')->controller(TopicController::class)->group(function () {
    Route::get('/get_sup_topics_by_main_topic/{main_topic_id}', 'getSupTopics')->name('topics.getSupTopics');
    Route::get('/get_main_topics_by_sup_topic/{sup_topic_id}', 'getMainTopics')->name('topics.getMainTopics');
});
Route::resource('topics', TopicController::class);


// Route::prefix('agendas')->controller(TopicAgendaController::class)->group(function () {
//     Route::get('/getAgendasByDepartment/{department_id}', 'getAgendasByDepartment')->name('agendas.getAgendasByDepartment');
// });
Route::resource('agendas', TopicAgendaController::class);


Route::prefix('sessions-departments')->controller(SessionDepartmentController::class)->group(function () {
    Route::get('/getInvitationFromDepartmentId/{department_id}', 'getInvitationFromDepartmentId')->name('sessions-departments.getInvitationFromDepartmentId');
    Route::put('/changeStatus/{session_id}', 'changeStatus')->name('sessions-departments.changeStatus');
    Route::get('/start-session/{session_id}', 'startSession')->name('sessions-departments.start-session');
    Route::get('/saveTime/{session_id}', 'saveTime')->name('sessions-departments.saveTime');
    Route::get('/fetch-attendance/{session_id}', 'fetchAttendance')->name('sessions-departments.fetch-attendance');
    Route::post('/save-attendance/{session_id}', 'saveAttendance')->name('sessions-departments.save-attendance');
    Route::get('/fetch-decision/{session_id}', 'fetchDecision')->name('sessions-departments.fetch-decision');
    Route::post('/save-decision/{session_id}', 'saveDecision')->name('sessions-departments.save-decision');
    Route::get('/fetch-vote/{session_id}', 'fetchVote')->name('sessions-departments.fetch-vote');
    Route::post('/save-vote/{session_id}', 'saveVote')->name('sessions-departments.save-vote');
    Route::get('/session-report/{session_id}', 'sessionReport')->name('sessions-departments.session-report');
    Route::get('/decision-approval/{session_id}', 'decisionApproval')->name('sessions-departments.decision-approval');
    Route::get('/report-details/{session_id}', 'reportDetails')->name('sessions-departments.report-details');
    Route::get('/getAgendasByDepartment/{department_id}', 'getAgendasByDepartment')->name('sessions-departments.getAgendasByDepartment');
});
Route::resource('sessions-departments', SessionDepartmentController::class);


Route::resource('college-councils', CollegeCouncilController::class);


Route::resource('sessions-faculties', FacultySessionController::class);
