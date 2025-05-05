<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BodySizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'neck'         => $this->neck,
            'shoulders'    => $this->shoulders,
            'arm_relaxed'  => $this->arm_relaxed,
            'arm_flexed'   => $this->arm_flexed,
            'forearm'      => $this->forearm,
            'wrist'        => $this->wrist,
            'chest'        => $this->chest,
            'stomach'      => $this->stomach,
            'waist'        => $this->waist,
            'hip'          => $this->hip,
            'thigh'        => $this->thigh,
            'calf'         => $this->calf,
            'ankle'        => $this->ankle,
            'measured_at'  => $this->measured_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
