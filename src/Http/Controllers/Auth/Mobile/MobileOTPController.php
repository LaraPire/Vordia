<?php

namespace Rayiumir\Vordia\Http\Controllers\Auth\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Rayiumir\Vordia\Http\Notifications\OTPSms;
use Rayiumir\Vordia\Http\Requests\Auth\MobileCheckOTPRequest;
use Rayiumir\Vordia\Http\Requests\Auth\MobileRequestOTPRequest;
use Rayiumir\Vordia\Http\Requests\Auth\MobileResendOTPRequest;

class MobileOTPController extends Controller
{

    public function showLoginForm(): View
    {
        return view('Vordia::auth.index');
    }

    public function requestOTP(MobileRequestOTPRequest $request)
    {
        $data = $request->validated();
        $mobile = $data['mobile'];
        try {
            $user = User::query()->where('mobile', $mobile)->first();
            $OTPCode = mt_rand(100000, 999999);
            $loginToken = Hash::make('DCDCojncd@cdjn%!!ghnjrgtn&&');

            if ($user) {
                $user->update([
                    'otp' => $OTPCode,
                    'login_token' => $loginToken
                ]);
            } else {
                $user = User::create([
                    'name' => $request->name ?? 'Null',
                    'email' => $request->email ?? 'null@gmail.com',
                    'password' => bcrypt($request->password ?? 'password'),
                    'mobile' => $mobile,
                    'otp' => $OTPCode,
                    'login_token' => $loginToken
                ]);
            }

            Notification::send($user, new OTPSms($OTPCode));

            return response(['login_token' => $loginToken], 200);
        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 422);
        }
    }

    public function checkOTP(MobileCheckOTPRequest $request)
    {
        try {
            $user = User::where('login_token', $request->login_token)->firstOrFail();

            if ($user->otp == $request->otp) {
                auth()->login($user, $remember = true);
                return response(['ورود با موفقیت انجام شد'], 200);
            } else {
                return response(['errors' => ['otp' => ['کد تاییدیه نادرست است']]], 422);
            }
        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 422);
        }
    }

    public function resendOTP(MobileResendOTPRequest $request)
    {
        try {

            $user = User::where('login_token', $request->login_token)->firstOrFail();
            $OTPCode = mt_rand(100000, 999999);
            $loginToken = Hash::make('DCDCojncd@cdjn%!!ghnjrgtn&&');

            $user->update([
                'otp' => $OTPCode,
                'login_token' => $loginToken
            ]);

            Notification::send($user, new OTPSms($OTPCode));

            return response(['login_token' => $loginToken], 200);
        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 422);
        }
    }
}
