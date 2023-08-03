<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// auth
Route::post('/user-registration',[UserController::class,'UserRegistration']);
Route::post('/user-login',[UserController::class,'UserLogin']);
Route::post('/send-otp',[UserController::class,'SendOTPCode']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/userLogout',[UserController::class,'UserLogout']);


// pages route
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/dashboard',[UserController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
// test PromotionalEmail Customer
Route::get('/test',function(){
    return view('email.PromotionalEmail');
});

// customer route
Route::get('/customerPage',[CustomerController::class,'CustomerPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/getCustomer',[CustomerController::class,'GetCustomer'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/create-customer',[CustomerController::class,'CreateCustomer'])->middleware([TokenVerificationMiddleware::class]);
Route::put('/update-customer',[CustomerController::class,'UpdateCustomer'])->middleware([TokenVerificationMiddleware::class]);
Route::delete('/delete-customer',[CustomerController::class,'DeleteCustomer'])->middleware([TokenVerificationMiddleware::class]);

//send promotional email
Route::post('/send-pmail',[UserController::class,'SendPromotionalMail']);
