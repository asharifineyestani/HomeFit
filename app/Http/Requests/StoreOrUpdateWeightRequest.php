<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateWeightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // یا اگر لازم شد بررسی خاص برای مجوز بده
    }

    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|min:20|max:300',
            'measured_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'weight.required'   => 'لطفاً وزن خود را وارد کنید.',
            'weight.numeric'    => 'وزن باید یک عدد باشد.',
            'weight.min'        => 'وزن نمی‌تواند کمتر از ۲۰ کیلوگرم باشد.',
            'weight.max'        => 'وزن نمی‌تواند بیشتر از ۳۰۰ کیلوگرم باشد.',
            'measured_at.date'  => 'فرمت تاریخ وارد شده نامعتبر است.',
        ];
    }
}
