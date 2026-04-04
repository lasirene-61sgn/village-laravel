<?php
/**
 * Debug Family Member Creation API Call
 * 
 * This script simulates the exact API call to create a family member
 * to identify what's causing the 500 internal server error.
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Get a customer for testing
$customer = \App\Models\Customer::first();
if (!$customer) {
    echo "No customer found. Please create a customer first.\n";
    exit(1);
}

echo "=== FAMILY MEMBER API DEBUG ===\n";
echo "Customer ID: " . $customer->id . "\n";
echo "Customer Name: " . $customer->name . "\n";
echo "Customer Admin ID: " . $customer->admin_id . "\n\n";

// Create test data matching what you sent
$familyMemberData = [
    'name' => 'jilla',
    'image' => '',
    'relationship' => 'son',
    'mobile' => '9012345678',
    'date_of_birth' => '2002-12-17',
    'anniversary_date' => null,
    'gotra' => 'Jian',
    'occupation' => 'B.E',
    'education' => 'B.E',
    'blood_group' => 'O+ve',
    'hobbies' => 'Eating, Sleeping',
    'native_place' => 'chennai',
    'notes' => 'noting',
    'matrimony' => false,
    'gender' => 'male'
];

echo "Test Data:\n";
print_r($familyMemberData);

// Simulate the API call manually
try {
    echo "\n=== SIMULATING API CALL ===\n";
    
    // Create a request object with the data
    $request = new \Illuminate\Http\Request();
    $request->setMethod('POST');
    $request->request->add($familyMemberData);
    
    // Set the authenticated customer
    \Illuminate\Support\Facades\Auth::shouldReceive('guard->user')
        ->andReturn($customer);
    
    // Get the API controller
    $controller = new \App\Http\Controllers\Api\Customer\CustomerController();
    
    // Try to validate the data
    echo "Validating data...\n";
    $validatedData = $request->validate([
        'name' => 'required|string|max:100',
        'image' => 'nullable|string',
        'relationship' => 'nullable|string|max:100',
        'mobile' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'anniversary_date' => 'nullable|date',
        'gotra' => 'nullable|string|max:100',
        'occupation' => 'nullable|string|max:100',
        'education' => 'nullable|string|max:100',
        'blood_group' => 'nullable|string|max:10',
        'hobbies' => 'nullable|string|max:255',
        'native_place' => 'nullable|string|max:100',
        'notes' => 'nullable|string',
        'matrimony' => 'nullable|boolean',
        'gender' => 'nullable|string|in:male,female,other',
    ]);
    
    echo "Validation passed!\n";
    print_r($validatedData);
    
    // Try to create the family member
    echo "\nCreating family member...\n";
    $familyMember = new \App\Models\FamilyMember($validatedData);
    $familyMember->customer_id = $customer->id;
    $familyMember->save();
    
    echo "Family member created successfully!\n";
    echo "Family Member ID: " . $familyMember->id . "\n";
    
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "VALIDATION ERROR:\n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "GENERAL ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
} catch (\Throwable $e) {
    echo "THROWABLE ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
?>