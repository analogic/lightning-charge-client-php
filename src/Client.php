<?php

namespace LightningCharge;

class Client
{
    protected $api;

    public function __construct(string $url, string $api_token = null)
    {
        $this->api = new \RestClient([
            'base_url' => rtrim($url, '/'),
            'curl_options' => $api_token ? [
                CURLOPT_USERPWD => 'api-token:' . $api_token
            ] : []
        ]);
    }

    /**
     * Creates new invoice
     *
     * @throws
     */
    public function invoice(InvoiceRequest $request): Invoice
    {
        $res = $this->api->post('/invoice', json_encode($request->toArray()), ['Content-Type' => 'application/json']);

        if ($res->info->http_code !== 201) throw new ClientException('Failed saving invoice', $res);

        return new Invoice($res->decode_response());
    }

    /**
     * Fetch invoice by ID
     *
     * @throws
     */
    public function fetch(string $invoice_id): Invoice
    {
        $res = $this->api->get('/invoice/' . urlencode($invoice_id));
        if ($res->info->http_code !== 200) throw new ClientException('Failed fetching invoice', $res);

        return new Invoice($res->decode_response());
    }

    /**
     * Fetch all invoices
     *
     * @return Invoice[]
     * @throws
     */
    public function fetchAll(): array
    {
        $res = $this->api->get('/invoices');
        if ($res->info->http_code !== 200) throw new ClientException('Failed fetching invoices', $res);

        return array_map(function ($invoiceData) { return new Invoice($invoiceData); }, $res->decode_response());
    }


    /**
     * Wait for an invoice to be paid.
     *
     * @param string $invoice_id
     * @param int $timeout the timeout in seconds
     * @return Invoice|bool|null the paid invoice if paid, false if the invoice expired, or null if the timeout is reached.
     * @throws
     */
    public function wait(string $invoice_id, int $timeout)
    {
        $res = $this->api->get('/invoice/' . urlencode($invoice_id) . '/wait?timeout=' . (int)$timeout);

        switch ($res->info->http_code) {
            // 200 OK: invoice is paid, return the updated invoice
            case 200:
                return new Invoice($res->decode_response());
            // 402 Payment Required: timeout reached without payment, invoice is still payable
            case 402:
                return null;
            // 410 Gone: invoice expired and can not longer be paid
            case 410:
                return false;

            default:
                throw new ClientException('Unknown status code', $res);
        }
    }

    /**
     * Register a new webhook.
     */
    public function registerHook(string $invoice_id, string $url): bool
    {
        $res = $this->api->post('/invoice/' . urlencode($invoice_id) . '/webhook', ['url' => $url]);
        if ($res->info->http_code !== 201)
            throw new ClientException('register hook failed', $res);
        return true;
    }
}
