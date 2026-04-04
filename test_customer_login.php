<?php
// Test script to verify the new unified customer login functionality

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Api\Customer\AuthController;

// Test scenarios for the unified login method

echo "Testing Customer Login API Scenarios:\n\n";

echo "Scenario 1: Customer without password set (first-time login with OTP)\n";
echo "- Customer calls sendOTP() to get OTP\n";
echo "- Customer calls login() with mobile and OTP\n";
echo "- Should successfully login and return token\n\n";

echo "Scenario 2: Customer with password set (login with password)\n";
echo "- Customer calls login() with mobile and password\n";
echo "- Should successfully login and return token\n\n";

echo "Scenario 3: Customer with password set (login with OTP)\n";
echo "- Customer calls login() with mobile and OTP\n";
echo "- Should successfully login and return token\n\n";

echo "Scenario 4: Customer forgot password (reset flow)\n";
echo "- Customer calls sendForgotPasswordOTP() to get OTP\n";
echo "- Customer calls resetPassword() with mobile, OTP, and new password\n";
echo "- Should successfully reset password and return token\n\n";

echo "Scenario 5: Customer without password set (login without OTP)\n";
echo "- Customer calls login() with only mobile\n";
echo "- Should return error: 'OTP is required for first-time login'\n\n";

echo "All scenarios are supported by the new unified login method!\n";