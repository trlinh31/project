<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\DB;
class EmailVerifyController extends Controller
{
    public function verify(Request $request)
    {


        $token = $request->bearerToken();

        $accessToken = DB::table('user_requests')->where('token', $token)->first();

        if (!$accessToken) {
            return response()->json(['token' => $token], 400);
        }

        $user = User::find($accessToken->user_id);
        $user->update(['email_verified_at' => now()]);


        $user->is_active = 1;
        $user->save();


        return response()->json(['message' => 'Email đã được xác thực thành công.']);
    }
    public function sendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = $user->createToken('EmailVerification')->accessToken;
            $verificationUrl = url('/verify-email?token=' . $token);
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));
        } else {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json(['message' => 'Email verification link sent successfully.']);
    }
}
