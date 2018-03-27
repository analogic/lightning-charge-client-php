<?php

namespace LightningCharge;

class ClientException extends \RuntimeException
{
    protected $res;

    public function __construct(string $message = "", \RestClient $res = null)
    {
        $this->res = $res;
        parent::__construct($message, $res->info->http_code);

        if ($this->res) {
            $this->message .= ' (code: ' . $this->res->info->http_code . ")\n";
            $this->message .= "Response:\n";
            $this->message .= $this->res->response;
        }
    }
}