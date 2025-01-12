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
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if(!$user){
            return response()->json(['message' => 'Email không tồn tại'], 404);
        }

        $user->update(['email_verified_at' => now()]);
        $user->is_active = 1;
        $user->save();

        return redirect('http://localhost:8081/auth/login');
    }
    public function sendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        $verificationUrl = url('/api/verify-email?email=' . $user->email);
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return response()->json(['message' => 'Chúng tôi đã gửi email xác thực cho bạn thành công']);
    }
}
