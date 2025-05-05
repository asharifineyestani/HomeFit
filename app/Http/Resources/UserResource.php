<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'mobile'          => $this->mobile,
            'gender'          => $this->gender,
            'height'          => $this->height,
            'blood_group'     => $this->blood_group,
            'birth_date'      => $this->birth_date,
            'city_id'         => $this->city_id,
            'referral_source' => $this->referral_source,
            'avatar_url'      => $this->avatar_url,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
