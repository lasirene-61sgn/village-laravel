<?php

// Test script for WhatsApp OTP functionality using MSG91 template API
require_once __DIR__ . '/vendor/autoload.php';

use App\Services\WhatsAppOTPService;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

echo "Testing WhatsApp OTP Service with MSG91 Template API...\n";

$whatsappOTPService = new WhatsAppOTPService();

// Test OTP generation
$otp = $whatsappOTPService->generateOTP();
echo "Generated OTP: " . $otp . "\n";

// Validate that it's a 6-digit number
if (is_numeric($otp) && strlen($otp) == 6) {
    echo "✓ OTP format is correct (6 digits)\n";
} else {
    echo "✗ OTP format is incorrect\n";
}

// Test mobile number formatting
$testNumbers = [
    '9876543210',
    '09876543210',
    '919876543210',
    '+919876543210',
    '91-9876543210'
];

foreach ($testNumbers as $number) {
    $formatted = $whatsappOTPService->formatMobileNumber($number);
    echo "Original: $number -> Formatted: $formatted\n";
}

// Test sending OTP (replace with a real mobile number for testing)
$testMobile = '919360777089'; // Replace with your test mobile number
echo "\nTesting OTP sending to: $testMobile\n";

$result = $whatsappOTPService->sendOTP($testMobile, $otp);

if ($result['success']) {
    echo "✓ OTP sent successfully!\n";
    echo "Response: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
} else {
    echo "✗ Failed to send OTP: " . $result['message'] . "\n";
    if (isset($result['error'])) {
        echo "Error details: " . $result['error'] . "\n";
    }
    if (isset($result['data'])) {
        echo "Response data: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\nWhatsApp OTP service test completed.\n";
echo "To test actual WhatsApp OTP sending, you need to:\n";
echo "1. Add your MSG91 credentials to .env file\n";
echo "2. Ensure your MSG91 template 'logintest' is properly configured\n";
echo "3. Call the sendOTP method with a real mobile number\n";