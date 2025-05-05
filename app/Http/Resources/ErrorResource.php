<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    protected $message;
    protected $status;
    protected $errors;

    public function __construct($message, $status = 400, $errors = [])
    {
        parent::__construct(null);
        $this->message = $message;
        $this->status = $status;
        $this->errors = $errors;
    }

    public function toArray($request)
    {
        return [
            'success' => false,
            'message' => $this->message,
            'errors' => $this->errors,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->status);
    }
}
