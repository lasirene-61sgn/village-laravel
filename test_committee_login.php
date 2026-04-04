<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CommitteePerson;
use Illuminate\Support\Facades\Hash;

// Create a test committee member if not exists
$testCommittee = CommitteePerson::firstOrCreate([
    'phone' => '1234567890',
], [
    'name' => 'Test President',
    'admin_id' => 1, // Assuming admin with ID 1 exists
    'phone' => '1234567890',
    'post_name' => 'President',
    'image_path' => null,
    'status' => 'active',
    'password' => Hash::make('password123'), // Hashed password
    'sort_order' => 0,
]);

echo "Test committee member created/updated:\n";
echo "ID: " . $testCommittee->id . "\n";
echo "Name: " . $testCommittee->name . "\n";
echo "Phone: " . $testCommittee->phone . "\n";
echo "Post: " . $testCommittee->post_name . "\n";
echo "Password is set: " . (!empty($testCommittee->password) ? 'Yes' : 'No') . "\n";

echo "\nYou can now test login with:\n";
echo "Phone: 1234567890\n";
echo "Password: password123\n";
echo "Allowed roles: president, vice president, treasurer, secretary, joint secretary, joint treasurer\n";