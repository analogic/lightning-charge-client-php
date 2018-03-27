<?php

namespace LightningCharge;

class InvoiceRequest
{
    protected $milliSatoshi;
    protected $currency;
    protected $amount;
    protected $expiry;
    protected $description = '';
    protected $metadata;

    public function getMilliSatoshi(): ?int
    {
        return $this->milliSatoshi;
    }

    public function setMilliSatoshi(?int $milliSatoshi): void
    {
        $this->milliSatoshi = $milliSatoshi;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param null|string $currency currency code for fiat-denominated payments
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata($metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getExpiry(): ?int
    {
        return $this->expiry;
    }

    /**
     * @param int $expiry seconds to invoice expiration (default is 1 hour)
     */
    public function setExpiry(?int $expiry): void
    {
        $this->expiry = $expiry;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        $data = [];

        if (!is_null($this->milliSatoshi)) $data['msatoshi'] = $this->milliSatoshi;
        if (!is_null($this->amount)) $data['amount'] = $this->amount;
        if (!is_null($this->currency)) $data['currency'] = $this->currency;
        if (!empty($this->metadata)) $data['metadata'] = $this->metadata;
        if (!empty($this->description)) $data['description'] = $this->description;

        return $data;
    }
}