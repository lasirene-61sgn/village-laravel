<?php
// Test Firebase Integration Script

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Customer;
use App\Services\RealTimeNotificationService;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Firebase Integration...\n";

// Test 1: Check if Firebase Messaging service is available
try {
    $messaging = app('firebase.messaging');
    echo "✓ Firebase Messaging service is available\n";
} catch (Exception $e) {
    echo "✗ Firebase Messaging service error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check if RealTimeNotificationService works
try {
    $service = new RealTimeNotificationService();
    echo "✓ RealTimeNotificationService instantiated successfully\n";
} catch (Exception $e) {
    echo "✗ RealTimeNotificationService error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Check if there are customers with FCM tokens
$customersWithTokens = Customer::whereNotNull('fcm_token')
    ->where('fcm_token', '!=', '')
    ->limit(5) // Just test with first 5
    ->get();

echo "Found " . $customersWithTokens->count() . " customers with FCM tokens\n";

if ($customersWithTokens->count() > 0) {
    // Test 4: Send a test notification to the first customer
    $firstCustomer = $customersWithTokens->first();
    echo "Testing notification to customer: " . $firstCustomer->name . " (ID: " . $firstCustomer->id . ")\n";
    
    $result = $service->sendRealTimeNotification(
        $firstCustomer->id,
        'test',
        'Test notification: Firebase integration is working!',
        ['test' => true, 'timestamp' => time()]
    );
    
    if ($result['success']) {
        echo "✓ Test notification sent successfully!\n";
    } else {
        echo "✗ Test notification failed: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "No customers with FCM tokens found. Please make sure at least one customer has registered their device token.\n";
    echo "You can use the /api/customer/update-device-token endpoint to register a token.\n";
}

echo "Firebase integration test completed.\n";