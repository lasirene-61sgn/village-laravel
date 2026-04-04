<?php
// Test banner API endpoint

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\BannerController;
use App\Services\NotificationsService;
use Illuminate\Support\Facades\Auth;

// Mock a request to test the store method
try {
    echo "Testing Banner API store method...\n";
    
    // Create a mock request with valid data
    $request = new Request();
    $request->merge([
        'image_path' => 'banners/test_banner.jpg',
        'status' => 'active'
    ]);
    
    // Mock admin user (assuming admin ID 1 exists)
    $mockAdmin = new class {
        public $id = 1;
        public function user() {
            return $this;
        }
        public function __get($name) {
            return $this->id;
        }
    };
    
    // Set the user on the request
    $request->setUserResolver(function() use ($mockAdmin) {
        return $mockAdmin;
    });
    
    // Create controller instance
    $controller = new BannerController();
    $notificationService = new NotificationsService();
    
    // Call the store method
    $response = $controller->store($request, $notificationService);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response content: " . $response->getContent() . "\n";
    
} catch (Exception $e) {
    echo "Error in API test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}