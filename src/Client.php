<?php

declare(strict_types=1);

namespace HTPDF;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class HTPDFException extends \Exception
{
    public string $error;
    public ?string $requestId;

    public function __construct(int $statusCode, string $error, string $message, ?string $requestId = null)
    {
        $this->error = $error;
        $this->requestId = $requestId;
        parent::__construct("[{$statusCode}] {$error}: {$message}", $statusCode);
    }
}

class Client
{
    private GuzzleClient $http;

    public function __construct(
        private string $apiKey,
        string $baseUrl = 'https://api.htpdf.net',
        float $timeout = 60.0,
    ) {
        $this->http = new GuzzleClient([
            'base_uri' => rtrim($baseUrl, '/'),
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
                'User-Agent' => 'htpdf-php/1.0.0',
            ],
        ]);
    }

    private function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->http->request($method, $path, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $body = json_decode($e->getResponse()->getBody()->getContents(), true) ?? [];
                throw new HTPDFException(
                    $e->getResponse()->getStatusCode(),
                    $body['error'] ?? 'unknown',
                    $body['message'] ?? $e->getMessage(),
                    $body['request_id'] ?? null,
                );
            }
            throw new HTPDFException(0, 'network_error', $e->getMessage());
        }
    }

    // ---- PDF Generation ----

    public function htmlToPdf(string $html, array $options = [], ?string $brandKitId = null): array
    {
        $payload = ['html' => $html];
        if ($options) $payload['options'] = $options;
        if ($brandKitId) $payload['brand_kit_id'] = $brandKitId;
        return $this->request('POST', '/v1/pdf/html', ['json' => $payload]);
    }

    public function urlToPdf(string $url, array $options = [], ?string $brandKitId = null): array
    {
        $payload = ['url' => $url];
        if ($options) $payload['options'] = $options;
        if ($brandKitId) $payload['brand_kit_id'] = $brandKitId;
        return $this->request('POST', '/v1/pdf/url', ['json' => $payload]);
    }

    public function markdownToPdf(string $markdown, string $theme = 'github', array $options = []): array
    {
        $payload = ['markdown' => $markdown, 'theme' => $theme];
        if ($options) $payload['options'] = $options;
        return $this->request('POST', '/v1/pdf/markdown', ['json' => $payload]);
    }

    public function templateToPdf(string $template, array $data, array $options = [], ?string $brandKitId = null): array
    {
        $payload = ['template' => $template, 'data' => $data];
        if ($options) $payload['options'] = $options;
        if ($brandKitId) $payload['brand_kit_id'] = $brandKitId;
        return $this->request('POST', '/v1/pdf/template', ['json' => $payload]);
    }

    // ---- Async PDF ----

    public function asyncPdf(string $sourceType, array $params = []): array
    {
        $payload = array_merge(['source_type' => $sourceType], $params);
        return $this->request('POST', '/v1/pdf/async', ['json' => $payload]);
    }

    public function getAsyncJob(string $jobId): array
    {
        return $this->request('GET', "/v1/pdf/async/{$jobId}");
    }

    public function waitForAsyncJob(string $jobId, float $pollInterval = 2.0, float $timeout = 300.0): array
    {
        $start = microtime(true);
        while (true) {
            $job = $this->getAsyncJob($jobId);
            if (in_array($job['status'], ['completed', 'failed'])) {
                return $job;
            }
            if (microtime(true) - $start > $timeout) {
                throw new \RuntimeException("Async job {$jobId} did not complete within {$timeout}s");
            }
            usleep((int) ($pollInterval * 1_000_000));
        }
    }

    // ---- Download ----

    public function download(string $pdfId): string
    {
        $response = $this->http->request('GET', "/v1/pdf/{$pdfId}/download");
        return $response->getBody()->getContents();
    }

    // ---- Screenshots ----

    public function htmlToScreenshot(string $html, array $options = []): array
    {
        $payload = ['html' => $html];
        if ($options) $payload['options'] = $options;
        return $this->request('POST', '/v1/screenshot/html', ['json' => $payload]);
    }

    public function urlToScreenshot(string $url, array $options = []): array
    {
        $payload = ['url' => $url];
        if ($options) $payload['options'] = $options;
        return $this->request('POST', '/v1/screenshot/url', ['json' => $payload]);
    }

    // ---- Document Hosting ----

    public function createHostedDocument(string $pdfId, array $options = []): array
    {
        $payload = array_merge(['pdf_id' => $pdfId], $options);
        return $this->request('POST', '/v1/documents', ['json' => $payload]);
    }

    public function listHostedDocuments(): array
    {
        return $this->request('GET', '/v1/documents');
    }

    public function deleteHostedDocument(string $docId): array
    {
        return $this->request('DELETE', "/v1/documents/{$docId}");
    }

    // ---- Merge ----

    public function merge(array $pdfIds, ?string $filename = null): array
    {
        $payload = ['pdf_ids' => $pdfIds];
        if ($filename) $payload['filename'] = $filename;
        return $this->request('POST', '/v1/pdf/merge', ['json' => $payload]);
    }
}
