<?php

namespace App\Services\Iugu;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class IuguClient
{
    protected string $apiBase = 'https://api.iugu.com/v1';

    public function __construct(protected readonly ?string $token)
    {
        if (! $this->token) {
            throw new RuntimeException('API token da Iugu não configurado.');
        }
    }

    public function listPlans(): array
    {
        $url = $this->apiBase.'/plans';
        $response = Http::withBasicAuth($this->token, '')->get($url);
        $this->logRequest('GET', $url, [], $response);

        return $this->handleResponse($response);
    }

    public function createPlan(array $payload): array
    {
        $url = $this->apiBase.'/plans';
        $response = Http::withBasicAuth($this->token, '')->post($url, $payload);
        $this->logRequest('POST', $url, $payload, $response);

        return $this->handleResponse($response);
    }

    public function updatePlan(string $identifier, array $payload): array
    {
        $url = $this->apiBase.'/plans/'.$identifier;
        $response = Http::withBasicAuth($this->token, '')->put($url, $payload);
        $this->logRequest('PUT', $url, $payload, $response);

        return $this->handleResponse($response);
    }

    public function deletePlan(string $identifier): void
    {
        $url = $this->apiBase.'/plans/'.$identifier;
        $response = Http::withBasicAuth($this->token, '')->delete($url);
        $this->logRequest('DELETE', $url, [], $response);

        $this->handleResponse($response, expectContent: false);
    }

    protected function handleResponse($response, bool $expectContent = true): array
    {
        if (! $response->successful()) {
            $body = $response->json();
            $message = is_string($body) ? $body : json_encode($body);
            throw new RuntimeException($message ?: 'Erro ao comunicar com a Iugu.');
        }

        if (! $expectContent) {
            return [];
        }

        return $response->json();
    }

    protected function logRequest(string $method, string $url, array $payload, $response): void
    {
        Log::info('Iugu API request', [
            'method' => $method,
            'url' => $url,
            'payload' => $payload,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);
    }
}
