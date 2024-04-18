<?php
namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;



class UserController extends Controller
{
    
        function LoginPage():View{
            return view('pages.auth.login-page');
        }

        function RegistrationPage():View{
            return view('pages.auth.registration-page');
        }
        function SendOtpPage():View{
            return view('pages.auth.send-otp-page');
        }
        function VerifyOTPPage():View{
            return view('pages.auth.verify-otp-page');
        }

        function ResetPasswordPage():View{
            return view('pages.auth.reset-pass-page');
        }

        function ProfilePage():View{
            return view('pages.dashboard.profile-page');
        }



    // Define a function named UserRegistration that takes a Request object as a parameter
    function UserRegistration(Request $request){
        try {
            // Try to create a new User using the Eloquent create method
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            // If the user creation is successful, return a JSON response with success status and message
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ], 200);

        } catch (Exception $e) {
            // If an exception (error) occurs during user creation, catch it and return a JSON response with failure status and message
            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed'
            ], 200);

        }
    }
// Define a function named UserLogin which takes a Request object as input
function UserLogin(Request $request){
    // Search for a user with the provided email and password
    $count = User::where("email", $request->input("email"))
        ->where("password", "=", $request->input("password"))
        ->select('id')->first();

    // Check if there is exactly one user matching the credentials
    if ($count !== null) {
        // If a single user is found, create a JWT token for the user's email and id
        $token = JWTToken::CreateToken($request->input("email"),$count->id);

        // Respond with a JSON indicating successful login along with the token
        // Set a cookie named 'token' with the JWT token, valid for 30 days
        return response()->json([
            "status" => "success",
            "message" => "User Login Successful",
        ], 200)->cookie('token',$token,60*24*30,'/');
    } else {
        // If no user or more than one user matches the credentials, return unauthorized status
        return response()->json([
            "status" => "Login Failed",
            "message" => "User Unauthorized"
        ], 401);
    }
}


    function SendOTPCode(Request $request){

        $email=$request->input('email');
        $otp=rand(100000,999999);
        $count=User::where('email','=',$email)->count();

        if($count==1){
            // OTP Email Address
            Mail::to($email)->send(new OTPMail($otp));
            // OTO Code Table Update
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '6 Digit OTP Code has been send to your email !'
            ],200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }
    }
    
    function VerifyOTP(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count==1){
            // Database OTP Update
            User::where('email','=',$email)->update(['otp'=>'0']);

            // Pass Reset Token Issue
            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful',
                
            ],200)->cookie('token',$token,60*24*30);

        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }
    

    function ResetPassword(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }

   

    function UserLogout(){
        return redirect('/userLogin')->cookie('token','',-1);
    }


    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);        
    }
    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    } 

}