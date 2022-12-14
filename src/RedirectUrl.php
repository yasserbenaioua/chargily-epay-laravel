<?php

namespace YasserBenaioua\Chargily;

use Illuminate\Support\Facades\Http;
use YasserBenaioua\Chargily\Exceptions\InvalidResponseException;

class RedirectUrl
{
    /**
     * @var string
     * The endpoint for create payments
     */
    protected string $api_url = 'http://epay.chargily.com.dz/api/invoice';

    /**
     * @var Configuration
     * Store the configurations wrapped over Configuration class
     */
    protected Configuration $configurations;

    /**
     * @var object
     */
    protected $cachedResponse = null;

    /**
     * @param configurations
     */
    public function __construct(Configuration $configurations)
    {
        $this->configurations = $configurations;
        $configurations->validateRedirectConfigurations();
    }

    /**
     * @method getRedirectUrl
     * This method is responsible for getting redirect url to payment
     *
     * @return string
     *
     * @throws InvalidResponseException
     */
    public function getRedirectUrl(): string
    {
        if ($response = $this->validateResponse() and $response !== true) {
            throw new InvalidResponseException("Invalid response status code ({$response->getStatusCode()}) when trying to get redirect url . More info (".$response->getBody()->getContents().')');
        }

        $response = $this->getResponse()->getBody()->getContents();
        $content_to_array = json_decode($response, true);

        return $content_to_array['checkout_url'];
    }

    /**
     * @method getResponse
     *
     * @return Request
     */
    protected function getResponse()
    {
        return $this->cachedResponse =
            ($this->cachedResponse)
                ? $this->cachedResponse
                : Http::withHeaders($this->buildHeaders())
                    ->withOptions([
                        'allow_redirects' => false,
                        'http_errors' => false,
                        'timeout' => $this->configurations->from('options')->getTimeout(),
                    ])
                    ->post($this->api_url, $this->buildRequest());
    }

    /**
     * @method validateResponse
     *
     * @return mixed
     */
    protected function validateResponse(): mixed
    {
        $response = $this->getResponse();
        if ($response->getStatusCode() !== 201) {
            return $response;
        }

        return true;
    }

    /**
     * @method buildHeaders
     *
     * @return array
     */
    protected function buildHeaders(): array
    {
        $headers = array_merge(
            [
                'Accept' => 'application/json',
                'X-Authorization' => config('chargily.key'),
            ],
            $this->configurations->from('options')->getHeaders()
        );

        return $headers;
    }

    /**
     * @method buildRequest
     *
     * @return array
     */
    protected function buildRequest(): array
    {
        return [
            'client' => $this->configurations->from('payment')->getClientName(),
            'client_email' => $this->configurations->from('payment')->getClientEmail(),
            'invoice_number' => $this->configurations->from('payment')->getNumber(),
            'amount' => $this->configurations->from('payment')->getAmount(),
            'discount' => $this->configurations->from('payment')->getDiscount(),
            'back_url' => $this->configurations->from('urls')->getBackUrl(),
            'webhook_url' => $this->configurations->from('urls')->getWebhookUrl(),
            'mode' => $this->configurations->getMode(),
            'comment' => $this->configurations->from('payment')->getDescription(),
        ];
    }
}
