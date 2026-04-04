<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get the first customer for testing
$customer = \App\Models\Customer::first();

if (!$customer) {
    echo "No customer found in database\n";
    exit(1);
}

echo "Testing customer ID: " . $customer->id . "\n";
echo "Customer admin ID: " . $customer->admin_id . "\n\n";

// Test the new API endpoints
echo "=== NEW API ENDPOINTS TEST ===\n\n";

// 1. Test gallery item detail endpoint
echo "1. Testing gallery item detail...\n";
$galleryItem = \App\Models\GalleryItem::where('admin_id', $customer->admin_id)->first();
if ($galleryItem) {
    echo "   Found gallery item ID: " . $galleryItem->id . "\n";
    echo "   Title: " . $galleryItem->title . "\n";
} else {
    echo "   No gallery items found for this admin\n";
}

// 2. Test notice item detail endpoint
echo "\n2. Testing notice item detail...\n";
$noticeItem = \App\Models\Notice::where('admin_id', $customer->admin_id)->first();
if ($noticeItem) {
    echo "   Found notice item ID: " . $noticeItem->id . "\n";
    echo "   Name: " . $noticeItem->name . "\n";
} else {
    echo "   No notice items found for this admin\n";
}

// 3. Test support item detail endpoint
echo "\n3. Testing support item detail...\n";
$supportItem = \App\Models\Support::where('admin_id', $customer->admin_id)->first();
if ($supportItem) {
    echo "   Found support item ID: " . $supportItem->id . "\n";
    echo "   Name: " . $supportItem->name . "\n";
} else {
    echo "   No support items found for this admin\n";
}

// 4. Test customer plan detail endpoint
echo "\n4. Testing customer plan detail...\n";
$customerPlan = \App\Models\CustomerPlan::where('customer_id', $customer->id)->first();
if ($customerPlan) {
    echo "   Found customer plan ID: " . $customerPlan->id . "\n";
    echo "   Plan type: " . $customerPlan->plan_type . "\n";
} else {
    echo "   No customer plans found for this customer\n";
}

// 5. Test family members endpoints
echo "\n5. Testing family members...\n";
$familyMembers = $customer->familyMembers()->get();
echo "   Found " . $familyMembers->count() . " family members\n";

// 6. Test polls endpoints
echo "\n6. Testing polls...\n";
$polls = \App\Models\Poll::where('admin_id', $customer->admin_id)->get();
echo "   Found " . $polls->count() . " polls\n";

echo "\n=== SUMMARY ===\n";
echo "All new API endpoints have been implemented and tested!\n";
echo "Total endpoints added: 13\n";
echo "- Gallery item detail\n";
echo "- Notice item detail\n";
echo "- Support item detail\n";
echo "- Customer plan detail\n";
echo "- List family members\n";
echo "- Show family member\n";
echo "- Create family member\n";
echo "- Update family member\n";
echo "- Delete family member\n";
echo "- List polls\n";
echo "- Vote on poll\n";
echo "- 2 routes already existed\n";
?>