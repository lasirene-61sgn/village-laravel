<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all active committee members ordered by sort_order
$members = \App\Models\CommitteePerson::where('status', 'active')
    ->orderBy('sort_order')
    ->get(['name', 'post_name', 'sort_order']);

echo "Current committee members ordered by sort_order:\n";
echo str_pad("Sort Order", 12) . str_pad("Post Name", 25) . "Name\n";
echo str_repeat("-", 50) . "\n";

foreach ($members as $member) {
    echo str_pad($member->sort_order, 12) . str_pad($member->post_name, 25) . $member->name . "\n";
}
?>