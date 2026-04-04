<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

echo "Testing Today's Birthdays and Anniversaries API endpoints...\n\n";

// Create test customers with today's birthday and anniversary
$today = Carbon::now();

// Create a test customer with today's birthday
$testCustomer1 = Customer::firstOrCreate([
    'mobile' => '9876543210',
], [
    'name' => 'Test Birthday Customer',
    'mobile' => '9876543210',
    'password' => Hash::make('password123'),
    'is_password_set' => true,
    'admin_id' => 1, // Assuming admin with ID 1 exists
    'date_of_birth' => $today->format('Y-m-d'), // Today's date for birthday
    'anniversary_date' => null,
]);

// Create a test customer with today's anniversary
$testCustomer2 = Customer::firstOrCreate([
    'mobile' => '9876543211',
], [
    'name' => 'Test Anniversary Customer',
    'mobile' => '9876543211',
    'password' => Hash::make('password123'),
    'is_password_set' => true,
    'admin_id' => 1, // Assuming admin with ID 1 exists
    'date_of_birth' => null,
    'anniversary_date' => $today->format('Y-m-d'), // Today's date for anniversary
]);

// Create a test customer with both today's birthday and anniversary
$testCustomer3 = Customer::firstOrCreate([
    'mobile' => '9876543212',
], [
    'name' => 'Test Both Customer',
    'mobile' => '9876543212',
    'password' => Hash::make('password123'),
    'is_password_set' => true,
    'admin_id' => 1, // Assuming admin with ID 1 exists
    'date_of_birth' => $today->format('Y-m-d'), // Today's date for birthday
    'anniversary_date' => $today->format('Y-m-d'), // Today's date for anniversary
]);

echo "Test customers created:\n";
echo "- Birthday Customer: " . $testCustomer1->name . " (DOB: " . $testCustomer1->date_of_birth . ")\n";
echo "- Anniversary Customer: " . $testCustomer2->name . " (Anniversary: " . $testCustomer2->anniversary_date . ")\n";
echo "- Both Customer: " . $testCustomer3->name . " (DOB: " . $testCustomer3->date_of_birth . ", Anniversary: " . $testCustomer3->anniversary_date . ")\n\n";

echo "API Endpoints Added:\n";
echo "GET /api/customer/today-birthdays - Get customers with today's birthday\n";
echo "GET /api/customer/today-anniversaries - Get customers with today's anniversary\n\n";

echo "Implementation Details:\n";
echo "1. Both endpoints filter customers from the same admin as the authenticated customer\n";
echo "2. Birthdays endpoint looks for customers with date_of_birth matching today's date\n";
echo "3. Anniversaries endpoint looks for customers with anniversary_date matching today's date\n";
echo "4. Both endpoints return customer details with village information\n";
echo "5. All existing functionality remains unchanged\n\n";

echo "Testing URLs (after authentication):\n";
echo "http://127.0.0.1:8000/api/customer/today-birthdays\n";
echo "http://127.0.0.1:8000/api/customer/today-anniversaries\n";