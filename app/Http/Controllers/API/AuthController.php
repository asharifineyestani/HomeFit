<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ErrorResource;
use App\Models\SmsLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    const MAX_COUNT_SMS_PER_DAY = 50;
    const INIT_COUNT = 59;


    public function requestCode($request)
    {
        $mobile = persianDigitsToEnglish($request->mobile);

        $lastSms = SmsLog::where('mobile', $mobile)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        if ($lastSms) {
            $secondsSinceLastSms = Carbon::now()->diffInSeconds($lastSms->created_at);
            if ($secondsSinceLastSms < self::INIT_COUNT) {
                return new ErrorResource(sprintf('لطفاً تا %d ثانیه بعد مجدد تلاش کنید.', self::INIT_COUNT), 429);
            }
        }

        $count = SmsLog::where('mobile', $mobile)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($count >= self::MAX_COUNT_SMS_PER_DAY) {
            return new ErrorResource('تعداد دفعات مجاز ارسال کد به پایان رسیده است.', 429);
        }

        $code = rand(1000, 9999);
        $log = SmsLog::create([
            'mobile' => $mobile,
            'ip' => $request->ip(),
            'code' => $code,
        ]);


        $user = User::query()->where('mobile', $mobile)->first();

        $this->sendSms($log);

    }

    public function checkMobile(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|regex:/(09)[0-9]{9}/'
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $mobile = persianDigitsToEnglish($request->mobile);

        $user = User::query()->where('mobile', $mobile)->first();

        if (empty($user->password)){
            $this->requestCode($request);
        }






        $isNewUser = !User::where('mobile', $mobile)->exists();

        return new SuccessResource(
            $isNewUser ? 'کد برای ثبت‌نام ارسال شد.' : 'کد ورود ارسال شد.',
            [
                'is_registered' => !$isNewUser,
                'timer' => self::INIT_COUNT,
                'has_password' => !empty($user->password),
            ]
        );
    }


    public function checkVerifyCode(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required',
                'code' => 'required|digits:4'
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $mobile = persianDigitsToEnglish($request->mobile);

        $validLog = SmsLog::where('mobile', $mobile)
            ->where('code', $request->code)
            ->exists();

        if (!$validLog) {
            return new ErrorResource('کد وارد شده اشتباه است.', 400);
        }

        $user = User::where('mobile', $mobile)->first();


        if ($user) {
            return new SuccessResource('ورود موفقیت‌آمیز.', [
                'is_registered' => true,
                'user' => new UserResource($user),
                'token' => $user->createToken('api-token')->plainTextToken
            ]);
        } else {
            return new SuccessResource('کد تایید شد.', [
                'is_registered' => false,
            ]);
        }


    }


    public function register(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required',
                'name' => 'required|string|max:255',
                'code' => 'required|digits:4',
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $mobile = persianDigitsToEnglish($request->mobile);

        $validLog = SmsLog::where('mobile', $mobile)
            ->where('code', $request->code)
            ->exists();

        if (!$validLog) {
            return new ErrorResource('کد وارد شده اشتباه است.', 400);
        }

        $exists = User::where('mobile', $mobile)->exists();
        if ($exists) {
            return new ErrorResource('این شماره موبایل قبلاً ثبت‌نام شده است.', 409);
        }

        $partner = null;
        $partnerSlug = $request->cookie('partner');

        if ($partnerSlug) {
            $partner = \App\Models\Partner::where('slug', $partnerSlug)->first();
        }

        $user = User::create([
            'name' => $request->name,
            'mobile' => $mobile,
            'partner_id' => $partner->user_id ?? 4,
            'avatar_path' => null,
        ]);

        Auth::login($user, true);

        return new SuccessResource('ثبت‌نام موفقیت‌آمیز.', [
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken
        ]);
    }

    public function loginWithPassword(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required',
                'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $credentials = [
            'mobile' => persianDigitsToEnglish($request->mobile),
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return new ErrorResource('رمز عبور اشتباه است.', 401);
        }

        $user = Auth::user();

        return new SuccessResource('ورود موفقیت‌آمیز.', [
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken
        ]);
    }

    public function resendCode(Request $request)
    {
         $this->requestCode($request);

        return new SuccessResource(
            'کد ارسال شد',
            [
                'timer' => self::INIT_COUNT,
            ]
        );
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|regex:/(09)[0-9]{9}/'
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $mobile = persianDigitsToEnglish($request->mobile);

        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            return new ErrorResource('کاربری با این شماره موبایل یافت نشد.', 404);
        }

        $lastSms = SmsLog::where('mobile', $mobile)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        if ($lastSms) {
            $secondsSinceLastSms = Carbon::now()->diffInSeconds($lastSms->created_at);
            if ($secondsSinceLastSms < self::INIT_COUNT) {
                return new ErrorResource(sprintf('لطفاً تا %d ثانیه بعد مجدد تلاش کنید.', self::INIT_COUNT), 429);
            }
        }

         $this->requestCode($request);

        return new SuccessResource(
            'کد ارسال شد',
            [
                'timer' => self::INIT_COUNT,
            ]
        );
    }

    public function setPassword(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required',
                'new_password' => 'required|min:6|confirmed',
                'code' => 'required|digits:4'
            ]);
        } catch (ValidationException $e) {
            return new ErrorResource('اطلاعات وارد شده صحیح نیست.', 422, $e->errors());
        }

        $mobile = persianDigitsToEnglish($request->mobile);

        $validLog = SmsLog::where('mobile', $mobile)
            ->where('code', $request->code)
            ->exists();

        if (!$validLog) {
            return new ErrorResource('کد وارد شده اشتباه است.', 400);
        }

        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            return new ErrorResource('کاربری با این شماره موبایل یافت نشد.', 404);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return new SuccessResource('رمز عبور با موفقیت تغییر کرد.', [
            'user' => new UserResource($user)
        ]);
    }


    protected function sendSms($log)
    {

        $project = config('app.project');
        $templateId = config("sms.templates.{$project}.verify");

//        dd($templateId);

        $sms = new \App\Http\Classes\WhiteSms();
        $sms->setMobile($log->mobile);
        $sms->setId($templateId);
        $sms->setParam('CODE', $log->code);
        $sms->send();
    }
}
