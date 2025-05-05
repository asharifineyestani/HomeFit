<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'avatar' => env('APP_URL').$this->avatar_path,
            'roles' => $this->roles->pluck('name'), // اگر رول‌ها داری
        ];
    }
}
