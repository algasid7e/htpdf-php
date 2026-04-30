<div align="center">
  
# 🐘 HTPDF PHP SDK

**Official PHP SDK for the HTPDF Generation API**

[![PHP 8.1+](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)](https://getcomposer.org/)

</div>

<br />

## 🌟 Overview

The official PHP SDK for [HTPDF](https://htpdf.net) — a high-performance PDF generation API running at the edge. Easily convert HTML or URLs into beautiful, print-ready PDFs directly from your PHP applications.

## 🚀 Key Features

- **HTML to PDF**: Convert any raw HTML string into a PDF instantly.
- **URL to PDF**: Snapshot live web pages with a single method call.
- **Async Processing**: Built-in support for long-running PDF jobs with automatic polling.
- **Document Hosting**: Securely host generated PDFs with expiration rules.

## ⚙️ Installation

```bash
composer require htpdf/htpdf
```

## 💻 Quick Start

```php
<?php
require_once 'vendor/autoload.php';
use HTPDF\Client;

$client = new Client('htpdf_live_your_api_key_here');

// Generate a PDF from HTML
$result = $client->htmlToPdf('<h1>Hello World</h1>');
echo "PDF ID: " . $result['pdf_id'] . "\n";

// Download the generated PDF to your local filesystem
$pdfBytes = $client->download($result['pdf_id']);
file_put_contents('output.pdf', $pdfBytes);
```

<hr />
<div align="center">
  <i>Seamless edge PDF generation for modern PHP.</i>
</div>