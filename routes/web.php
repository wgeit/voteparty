<?php
use App\Http\Controllers\SpecialEventController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Special Event routes - controller handles auth check via getCurrentUser()
Route::get('/SpecialEvent/VoteRegister', [App\Http\Controllers\SpecialEventController::class, 'voteRegisterIndex'])->name('Special.Vote.Register');
Route::get('/SpecialEvent/Dashboard', [App\Http\Controllers\SpecialEventController::class, 'dashboardIndex'])->name('Special.Dashboard');

Route::post('/SpecialEvent/Vote', [App\Http\Controllers\SpecialEventController::class, 'voteRegisterIndex']);
Route::get('/User/IT/SpecialEvent/getEmployeeList', [App\Http\Controllers\SpecialEventController::class, 'getEmployeeList'])->name('SpecialEvent.getEmployeeList'); 
Route::post('/User/IT/SpecialEvent/saveCheckIn', [App\Http\Controllers\SpecialEventController::class, 'saveCheckIn'])->name('SpecialEvent.saveCheckIn');
Route::post('/User/IT/SpecialEvent/addEmployee', [App\Http\Controllers\SpecialEventController::class, 'addEmployee'])->name('SpecialEvent.addEmployee');
Route::get('/User/IT/SpecialEvent/exportExcel', [App\Http\Controllers\SpecialEventController::class, 'exportExcel'])->name('SpecialEvent.exportExcel');
Route::get('/User/IT/SpecialEvent/MangeImage', [App\Http\Controllers\SpecialEventController::class, 'manageImagesIndex']);
Route::get('/User/IT/SpecialEvent/getImages', [App\Http\Controllers\SpecialEventController::class, 'getImages'])->name('SpecialEvent.getImages');
Route::get('/User/IT/SpecialEvent/exportImagesExcel', [App\Http\Controllers\SpecialEventController::class, 'exportImagesExcel'])->name('SpecialEvent.exportImagesExcel');
Route::post('/User/IT/SpecialEvent/uploadImage', [App\Http\Controllers\SpecialEventController::class, 'uploadImage'])->name('SpecialEvent.uploadImage');
Route::post('/User/IT/SpecialEvent/updateImage', [App\Http\Controllers\SpecialEventController::class, 'updateImage'])->name('SpecialEvent.updateImage');
Route::post('/User/IT/SpecialEvent/deleteImage', [App\Http\Controllers\SpecialEventController::class, 'deleteImage'])->name('SpecialEvent.deleteImage');
Route::get('/User/IT/SpecialEvent/getDashboardData', [App\Http\Controllers\SpecialEventController::class, 'getDashboardData'])->name('SpecialEvent.getDashboardData');
Route::post('/User/IT/SpecialEvent/updateEventStatus', [App\Http\Controllers\SpecialEventController::class, 'updateEventStatus'])->name('SpecialEvent.updateEventStatus');
Route::get('/User/IT/SpecialEvent/getEventStatus', [App\Http\Controllers\SpecialEventController::class, 'getEventStatus']);

Route::get('/User/IT/SpecialEvent/Vote',[SpecialEventController::class,'empVoteIndex']);
Route::post('/User/IT/SpecialEvent/verifyCard',[SpecialEventController::class,'verifyCard']);
Route::post('/User/IT/SpecialEvent/submitVotes',[SpecialEventController::class,'submitVotes']);
Route::get('/User/IT/SpecialEvent/getVoteImages',[SpecialEventController::class,'getVoteImages']);

Route::get('/test-db', function () {
    dd(config('database.connections.mysql'));
});
