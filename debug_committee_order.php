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

// Check how they would be mapped
$positionOrder = [
    'president' => 0,
    'vice president' => 1,
    'secretary' => 2,
    'joint secretary' => 3,
    'treasurer' => 4,
    'joint treasurer' => 5,
];

echo "\nMapping check:\n";
foreach ($members as $member) {
    $postName = strtolower($member->post_name);
    $sortOrder = $positionOrder[$postName] ?? 100;
    echo "'" . $member->post_name . "' -> '" . $postName . "' -> sortOrder: " . $sortOrder . "\n";
}
?>