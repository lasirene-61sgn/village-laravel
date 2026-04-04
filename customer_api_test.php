<?php

// Simple API test script for customer endpoints
// This script demonstrates how to use the new customer API endpoints

echo "Customer API Test Script\n";
echo "========================\n\n";

// Base URL for API endpoints
$base_url = 'http://localhost:8000/api';

echo "1. Testing customer authentication endpoints:\n";
echo "   - POST /api/customer/send-otp\n";
echo "   - POST /api/customer/verify-otp\n";
echo "   - POST /api/customer/login\n";
echo "   - POST /api/customer/set-password\n\n";

echo "2. Testing customer protected endpoints (require authentication):\n";
echo "   - GET /api/customer/profile\n";
echo "   - PUT /api/customer/profile\n";
echo "   - GET /api/customer/plans\n";
echo "   - GET /api/customer/customers\n";
echo "   - GET /api/customer/gallery\n";
echo "   - GET /api/customer/banner\n";
echo "   - GET /api/customer/notice\n";
echo "   - GET /api/customer/village\n";
echo "   - GET /api/customer/event\n";
echo "   - GET /api/customer/news\n";
echo "   - GET /api/customer/support\n";
echo "   - GET /api/customer/committee\n";
echo "   - GET /api/customer/customer-plan\n";
echo "   - POST /api/customer/logout\n\n";

echo "Example usage:\n";
echo "1. Send OTP:\n";
echo "   curl -X POST $base_url/customer/send-otp \\\n";
echo "        -H \"Content-Type: application/json\" \\\n";
echo "        -d '{\"mobile\": \"9876543210\"}'\n\n";

echo "2. Verify OTP and get token:\n";
echo "   curl -X POST $base_url/customer/verify-otp \\\n";
echo "        -H \"Content-Type: application/json\" \\\n";
echo "        -d '{\"mobile\": \"9876543210\", \"otp\": \"123456\"}'\n\n";

echo "3. Use token to access protected endpoints:\n";
echo "   curl -X GET $base_url/customer/profile \\\n";
echo "        -H \"Authorization: Bearer YOUR_TOKEN_HERE\"\n\n";

echo "All customer endpoints are now available as API routes!\n";
?>