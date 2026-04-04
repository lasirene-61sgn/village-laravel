<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Your exact post names
$postNames = [
    'Vice - President',
    'Joint-Secretary',
    'Joint- Treasurer',
    'President',
    'Secretary',
    'Treasurer'
];

echo "Testing with your exact post names:\n";

// Define the order for common committee positions with exact matches
$positionOrder = [
    'president' => 0,
    'vice - president' => 1,
    'secretary' => 2,
    'joint-secretary' => 3,
    'treasurer' => 4,
    'joint- treasurer' => 5,
];

foreach ($postNames as $postName) {
    $lowerPostName = strtolower($postName);
    
    // Set sort order based on position, default to 100 for unknown positions
    $sortOrder = $positionOrder[$lowerPostName] ?? 100;
    
    echo "'" . $postName . "' -> '" . $lowerPostName . "' -> sortOrder: " . $sortOrder . "\n";
}

echo "\nRunning the seeder logic...\n";

// Apply the seeder logic
foreach ($postNames as $postName) {
    $committeeMember = new \App\Models\CommitteePerson();
    $committeeMember->post_name = $postName;
    
    $lowerPostName = strtolower($postName);
    $sortOrder = $positionOrder[$lowerPostName] ?? 100;
    
    echo "Would set '" . $postName . "' to sort_order: " . $sortOrder . "\n";
}
?>