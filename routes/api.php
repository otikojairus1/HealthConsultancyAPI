<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, "register"]);
Route::post('/login', [UserController::class, "login"]);

//doctors logic

Route::post('/add/doctor', [DoctorController::class, "add"]);
Route::post('/doctor/login', [DoctorController::class, "login"]);
Route::post('/doctor/change/status', [DoctorController::class, "online"]);
Route::get('/doctors', [DoctorController::class, "listdoctors"]);
Route::get('/available/doctors', [DoctorController::class, "availableDoctors"]);

//appointments
Route::post('/add/appointment', [AppointmentController::class, "add"]);
Route::get('/approve/appointment/{id}', [AppointmentController::class, "approve"]);
Route::post('/approved/appointments/doctors', [AppointmentController::class, "confirmedAppointments"]);
Route::post('/pending/appointments/doctors', [AppointmentController::class, "booked"]);
Route::post('/pending/appointments/patient', [AppointmentController::class, "pendingApproval"]);
Route::post('/approved/appointments/patient', [AppointmentController::class, "ApprovedPatients"]);

//direct messages
Route::post('/add/message', [MessageController::class, "add"]);
Route::post('/inbox', [MessageController::class, "inbox"]);



