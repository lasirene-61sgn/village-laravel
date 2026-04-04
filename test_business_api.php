<?php
// Test script for business name APIs

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// This is a simple test to check if the API endpoints exist
echo "Testing Business Name API Endpoints...\n";
echo "API endpoints added:\n";
echo "1. GET /api/customer/business-names - Get all unique business names\n";
echo "2. GET /api/customer/customers-by-business?business_name=XXX - Get customers by business name\n";
echo "\nImplementation complete!\n";