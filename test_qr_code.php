<?php

require_once 'vendor/autoload.php';

use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Test basic QR code generation
try {
    $qrCode = QrCode::size(300)->generate('https://example.com/test-event');
    echo "QR Code generated successfully!\n";
    echo "QR Code length: " . strlen($qrCode) . " characters\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}