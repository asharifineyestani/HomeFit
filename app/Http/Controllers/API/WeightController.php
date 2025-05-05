<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WeightResource;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Requests\StoreOrUpdateWeightRequest;

class WeightController extends Controller
{
    public function storeOrUpdate(StoreOrUpdateWeightRequest $request)
    {
        $user = $request->user();

        // اگر تاریخ ارسال نشده بود، امروز در نظر گرفته می‌شود
        $measuredDate = $request->filled('measured_date')
            ? Carbon::parse($request->input('measured_date'))->toDateString()
            : now()->toDateString();

        // بررسی وجود رکورد قبلی برای همان تاریخ و کاربر
        $existing = Weight::where('user_id', $user->id)
            ->where('measured_date', $measuredDate)
            ->first();

        if ($existing) {
            $existing->update([
                'weight' => $request->input('weight'),
            ]);

            return (new WeightResource($existing))->additional([
                'message' => 'وزن شما برای این روز به‌روزرسانی شد.',
            ]);
        }

        $weight = Weight::create([
            'user_id'       => $user->id,
            'weight'        => $request->input('weight'),
            'measured_date' => $measuredDate,
        ]);

        return (new WeightResource($weight))->additional([
            'message' => 'وزن شما با موفقیت ثبت شد.',
        ]);
    }
}
