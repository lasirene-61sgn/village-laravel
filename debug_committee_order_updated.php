<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all committee members and their post names
$members = \App\Models\CommitteePerson::select('post_name')->distinct()->get();
echo "Current post names in database:\n";
foreach ($members as $member) {
    echo "- '" . $member->post_name . "'\n";
}

// Check how they would be mapped with the updated logic
$positionOrder = [
    'president' => 0,
    'vice president' => 1,
    'vice-president' => 1,
    'secretary' => 2,
    'joint secretary' => 3,
    'joint-secretary' => 3,
    'treasurer' => 4,
    'joint treasurer' => 5,
    'joint-treasurer' => 5,
];

echo "\nMapping check with updated logic:\n";
foreach ($members as $member) {
    $postName = strtolower($member->post_name);
    
    // Handle common variations - normalize spaces and hyphens
    $normalizedPostName = preg_replace('/[^a-z]/', '', $postName);
    
    // Set sort order based on position, default to 100 for unknown positions
    $sortOrder = $positionOrder[$postName] ?? $positionOrder[$normalizedPostName] ?? 100;
    
    echo "'" . $member->post_name . "' -> '" . $postName . "' -> '" . $normalizedPostName . "' -> sortOrder: " . $sortOrder . "\n";
}
?>