<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    protected $message;

    public function __construct($message, $resource)
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'message' => $this->message,
            'data' => parent::toArray($request),
        ];
    }
}
