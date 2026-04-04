<?php
// Test banner creation

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Banner;

// Test creating a banner
try {
    echo "Testing banner creation...\n";
    
    // Create a test banner
    $banner = new Banner();
    $banner->admin_id = 1;  // Using a default admin ID
    $banner->image_path = 'test/test.jpg';
    $banner->status = 'active';
    $banner->save();
    
    echo "Banner created successfully with ID: " . $banner->id . "\n";
    echo "Banner data: " . json_encode($banner->toArray()) . "\n";
    
    // Clean up test banner
    $banner->delete();
    echo "Test banner deleted.\n";
} catch (Exception $e) {
    echo "Error creating banner: " . $e->getMessage() . "\n";
    echo "Error trace: " . $e->getTraceAsString() . "\n";
}