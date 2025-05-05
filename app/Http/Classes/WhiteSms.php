<?php

namespace App\Http\Classes;


use App\Models\SmsSentLog;
use App\Models\SmsTemplate;
use Cryptommer\Smsir\Classes\Smsir;

class WhiteSms
{
    private ?int $templateId = null;
    private string $mobile;
    private array $parameters = [];

    public function setParam($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function setId($templateId)
    {
        $this->templateId = $templateId;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }


    public function adaptedParameters()
    {
        $adaptedParams = [];

        foreach ($this->parameters as $key => $value) {
            $adaptedParams[] = new \Cryptommer\Smsir\Objects\Parameters($key, $value);
        }

        return $adaptedParams;

    }

    public function sendWhiteSms()
    {
        $smsir = new Smsir(env('SMSIR_LINE_NUMBER'), env('SMSIR_API_KEY'));
        $send = $smsir->Send();
        $send->Verify($this->mobile, $this->templateId, $this->adaptedParameters());
    }

    public function send()
    {
        $this->sendWhiteSms();
        $this->storeLog();
    }


    public function storeLog()
    {

        $smsTemplate = SmsTemplate::where('template_id', $this->templateId)->first();

        if (!$smsTemplate)
            return null;

        $params = $this->parameters;


        $patterns = array_map(
            function ($n) {
                return '/#' . $n . '#/';
            },
            array_keys($params)
        );


        $body = preg_replace($patterns, array_values($params), $smsTemplate->body);


        SmsSentLog::create([
            'body' => $body,
            'mobile' => $this->mobile,
        ]);


    }

}
