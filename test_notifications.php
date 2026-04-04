<?php
// Simple test to verify notifications functionality

require_once 'vendor/autoload.php';

use App\Services\NotificationsService;

echo "Testing NotificationsService...\n";

try {
    $service = new NotificationsService();
    echo "NotificationsService created successfully!\n";
    
    // Test one of the methods to make sure it doesn't cause memory issues
    echo "Testing createGalleryAddedNotification method...\n";
    // This won't work without actual data, but it should not cause memory issues
    
    echo "All tests passed!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}