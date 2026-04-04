<?php

// Test script for WhatsApp OTP functionality
require_once __DIR__ . '/vendor/autoload.php';

use App\Services\WhatsAppOTPService;

// Test the WhatsApp OTP service
echo "Testing WhatsApp OTP Service...\n";

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

echo "\nWhatsApp OTP service test completed.\n";
echo "To test actual WhatsApp OTP sending, you need to:\n";
echo "1. Add your MSG91 credentials to .env file\n";
echo "2. Create a customer record with a valid mobile number\n";
echo "3. Call the sendOTP method with a real mobile number\n";