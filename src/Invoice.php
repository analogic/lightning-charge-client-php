<?php

namespace LightningCharge;

class Invoice
{
    protected $id;
    protected $rhash;
    protected $payreq;
    protected $status;
    protected $msatoshi;
    protected $currency;
    protected $amount;
    protected $createdAt;
    protected $expiresAt;
    protected $metadata;

    public function __construct(\stdClass $data)
    {
        $this->id = $data->id;
        $this->rhash = $data->rhash;
        $this->payreq = $data->payreq;
        $this->msatoshi = $data->msatoshi;

        if (property_exists($data, 'quoted_currency')) {
            $this->currency = $data->quoted_currency;
            $this->amount = $data->quoted_amount;
        }

        $ca = new \DateTime();
        $ca->setTimestamp($data->created_at);
        $this->createdAt = $ca;

        $ea = new \DateTime();
        $ea->setTimestamp($data->expires_at);
        $this->expiresAt = $ea;

        if (property_exists($data, 'metadata')) {
            $this->metadata = $data->metadata;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRhash(): string
    {
        return $this->rhash;
    }

    public function getPayreq(): string
    {
        return $this->payreq;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMilliSatoshi(): int
    {
        return $this->msatoshi;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }


}