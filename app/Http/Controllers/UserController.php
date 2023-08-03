<?php

namespace App\Http\Controllers;

use App\Mail\PromotionalMail;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // pages method
    public function LoginPage(): view{
        return view('pages.auth.login-page');
    }// end method

    public function RegistrationPage(): view{
        return view('pages.auth.registration-page');
    }// end method

    public function SendOtpPage(): view{
        return view('pages.auth.send-otp-page');
    }// end method

    public function VerifyOTPPage(): view{
        return view('pages.auth.verify-otp-page');
    }// end method

    public function ResetPasswordPage(): view{
        return view('pages.auth.reset-pass-page');
    }// end method
    public function DashboardPage(){
        return view('pages.dashboard.dashboard-page');
    }
    // End pages  
      
    public function UserRegistration(Request $request){
       try{
            $request->validate([
                'name'      =>'required|string',
                'email'     =>'required|unique:users,email',
                'phone'     =>'required|string',
                'password'  =>'required|string'
            ]);
            User::create([
                'name'      =>$request->input('name'),
                'email'     =>$request->input('email'),
                'phone'     =>$request->input('phone'),
                'password'  =>$request->input('password')
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'User Registraion Successful'
            ],200);
       }catch(Exception $e){
            return response()->json([
                'status'  => 'failled',
                'message' => "User Registration failled",
            ], 200);
       }
    }// end method
    function UserLogin(Request $request){
        $count = User::where('email',$request->input('email'))->where('password',$request->input('password'))
        ->select('id')->first();
        if($count !== null){
            $token = JWTToken::CreateToken($request->input('email'),$count->id);
            return response()->json([
                'status'=>'success',
                'message'=>'User Login Successful'
            ])->cookie('token',$token,60*24*30);
        }else{
            return response()->json([
                'status' => 'failled',
                'message' => 'unauthorized',
            ], 200);
        }
    }// end method

    function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(100000,999999);
        $count = User::where('email',$email)->count();
        if($count === 1){
            Mail::to($email)->send(new OTPMail($otp));

            User::where('email',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 Digit OTP Code has been send to your email !',
            ], 200);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ]);
        }
        
    }// end method
    function VerifyOTP(Request $request){
        $email  = $request->input('email');
        $otp    = $request->input('otp');
        $count  = User::where('email',$email)
            ->where('otp',$otp)->count();

        if($count===1){
            User::where('email',$email)->update(['otp'=>'0']);
            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful'
            ], 200)->cookie('token',$token,60*24*30);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 401);
        }
    }// end method
    function ResetPassword(Request $request){
        try{
            $email      = $request->header('email');
            $password   = $request->input('password');

            User::where('email',$email)->update(['password'=>$password]);
            return response()->json([
                'status'=>'success',
                'message'=>'Password Reset successful'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>'failled',
                'message'=>'Something went wrong!'
            ]);
        }
    }// end method
    function UserLogout(){
        return redirect('/userLogin')->cookie('token','',-1);
    }// end method

    // send promotional mail
    function SendPromotionalMail(Request $request){
        try{
            $email = Customer::pluck('email');
            $link = $request->input('link');
            Mail::to($email)->send(new PromotionalMail($link));
            return response()->json([
                'status'=>'success',
                'message'=>'Email send successful'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>'failled',
                'message'=>'Email send Failled'
            ]);
        }
    }// end method
    
}
