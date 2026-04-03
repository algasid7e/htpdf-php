# HTPDF PHP SDK

Generate PDFs, screenshots, and hosted documents via the [HTPDF API](https://htpdf.net).

## Install

```bash
composer require htpdf/htpdf
```

## Quick Start

```php
<?php
require_once 'vendor/autoload.php';

use HTPDF\Client;

$client = new Client('htpdf_live_...');

// HTML to PDF
$result = $client->htmlToPdf('<h1>Hello World</h1>');
echo $result['pdf_id'] . "\n";

// Download
$pdfBytes = $client->download($result['pdf_id']);
file_put_contents('output.pdf', $pdfBytes);

// URL to PDF
$result = $client->urlToPdf('https://example.com');

// Async PDF (Pro+)
$job = $client->asyncPdf('html', ['html' => '<h1>Async</h1>']);
$completed = $client->waitForAsyncJob($job['job_id']);

// Document Hosting (Pro+)
$doc = $client->createHostedDocument($result['pdf_id'], [
    'expires_in_hours' => 24,
]);
echo $doc['url'] . "\n"; // /d/abc12345
```

## License

MIT
