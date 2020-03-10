<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Validator;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
                    return response()->json(['code'=>'02','status'=>'401','data'=>$validator->errors()], 200);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'code'=>'04',
                'status'=>'404',
                'data' => "We can't find a user with that e-mail address."], 200);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        );
        if ($passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'code'=>'0',
            'status'=>'200',
            'data' => 'We have e-mailed your password reset link!'
        ],200);
    }


    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string',
        ]);
        if ($validator->fails()) {
                    return response()->json(['code'=>'02','status'=>'401','data'=>$validator->errors()], 200);
        }
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'code'=>'04',
                'status'=>'404',
                'data' => 'This password reset token is invalid.'
            ], 200);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'code'=>'04',
                'status'=>'404',
                'data' => 'This password reset token is expired',
            ], 200);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'code'=>'04',
                'status'=>'404',
                'data' => "We can't find a user with that e-mail address."
            ], 200);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json([
            'code'=>'0',
            'status'=>'200',
            'data' => $user,
        ], 200);
    }
}