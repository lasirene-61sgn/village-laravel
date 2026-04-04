<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate getting a customer (using the first customer for testing)
$customer = \App\Models\Customer::first();

if (!$customer) {
    echo "No customer found in database\n";
    exit(1);
}

echo "Testing API response for customer ID: " . $customer->id . "\n";
echo "Customer admin ID: " . $customer->admin_id . "\n\n";

// Get committee members as the API would
$committeeMembers = \App\Models\CommitteePerson::where('admin_id', $customer->admin_id)
    ->where('status', 'active')
    ->orderBy('sort_order', 'asc')
    ->get();

echo "Committee members in API response order:\n";
echo str_pad("Sort Order", 12) . str_pad("Post Name", 25) . "Name\n";
echo str_repeat("-", 50) . "\n";

foreach ($committeeMembers as $member) {
    echo str_pad($member->sort_order, 12) . str_pad($member->post_name, 25) . $member->name . "\n";
}

echo "\nAPI would return " . $committeeMembers->count() . " committee members in this order.\n";
?>