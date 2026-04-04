<?php
/**
 * Event RSVP API Test Script
 * 
 * This script demonstrates how to test the event RSVP functionality via API calls
 * that would typically be made from Postman or a mobile app.
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get a customer for testing
$customer = \App\Models\Customer::first();
if (!$customer) {
    echo "No customer found. Please create a customer first.\n";
    exit(1);
}

// Create a personal access token for API authentication
$token = $customer->createToken('test-token')->plainTextToken;

echo "=== EVENT RSVP API TEST ===\n";
echo "Customer ID: " . $customer->id . "\n";
echo "Customer Name: " . $customer->name . "\n";
echo "Authorization Token: " . $token . "\n\n";

// Get an event for testing
$event = \App\Models\Event::where('admin_id', $customer->admin_id)->first();
if (!$event) {
    echo "No events found for this customer's admin. Please create an event first.\n";
    exit(1);
}

echo "Event ID: " . $event->id . "\n";
echo "Event Name: " . $event->name . "\n\n";

// 1. Test RSVP to an event (Accept)
echo "1. Testing RSVP Accept...\n";
$rsvpAcceptData = [
    'status' => 'accepted',
    'note' => 'Looking forward to attending!',
    'adults_count' => 2,
    'children_count' => 1
];

echo "Request Data:\n";
print_r($rsvpAcceptData);

// Simulate API call to accept RSVP
$rsvpAccept = \App\Models\EventRSVP::updateOrCreate(
    [
        'event_id' => $event->id,
        'customer_id' => $customer->id,
    ],
    [
        'status' => $rsvpAcceptData['status'],
        'note' => $rsvpAcceptData['note'],
        'adults_count' => $rsvpAcceptData['adults_count'],
        'children_count' => $rsvpAcceptData['children_count']
    ]
);

echo "RSVP Accept Result:\n";
echo "  Status: " . $rsvpAccept->status . "\n";
echo "  Note: " . $rsvpAccept->note . "\n";
echo "  Adults: " . $rsvpAccept->adults_count . "\n";
echo "  Children: " . $rsvpAccept->children_count . "\n\n";

// 2. Test RSVP to an event (Decline)
echo "2. Testing RSVP Decline...\n";
$rsvpDeclineData = [
    'status' => 'declined',
    'note' => 'Sorry, can\'t make it this time.'
];

echo "Request Data:\n";
print_r($rsvpDeclineData);

// Simulate API call to decline RSVP
$rsvpDecline = \App\Models\EventRSVP::updateOrCreate(
    [
        'event_id' => $event->id,
        'customer_id' => $customer->id,
    ],
    [
        'status' => $rsvpDeclineData['status'],
        'note' => $rsvpDeclineData['note'],
        'adults_count' => 0,
        'children_count' => 0
    ]
);

echo "RSVP Decline Result:\n";
echo "  Status: " . $rsvpDecline->status . "\n";
echo "  Note: " . $rsvpDecline->note . "\n\n";

// 3. Test RSVP to an event (Maybe)
echo "3. Testing RSVP Maybe...\n";
$rsvpMaybeData = [
    'status' => 'maybe',
    'note' => 'Still deciding, will confirm later.'
];

echo "Request Data:\n";
print_r($rsvpMaybeData);

// Simulate API call for maybe RSVP
$rsvpMaybe = \App\Models\EventRSVP::updateOrCreate(
    [
        'event_id' => $event->id,
        'customer_id' => $customer->id,
    ],
    [
        'status' => $rsvpMaybeData['status'],
        'note' => $rsvpMaybeData['note'],
        'adults_count' => 0,
        'children_count' => 0
    ]
);

echo "RSVP Maybe Result:\n";
echo "  Status: " . $rsvpMaybe->status . "\n";
echo "  Note: " . $rsvpMaybe->note . "\n\n";

// 4. Test getting RSVP status
echo "4. Testing Get RSVP Status...\n";
$currentRsvp = \App\Models\EventRSVP::where('event_id', $event->id)
    ->where('customer_id', $customer->id)
    ->first();

if ($currentRsvp) {
    echo "Current RSVP Status:\n";
    echo "  Status: " . $currentRsvp->status . "\n";
    echo "  Note: " . $currentRsvp->note . "\n";
    echo "  Adults: " . $currentRsvp->adults_count . "\n";
    echo "  Children: " . $currentRsvp->children_count . "\n";
} else {
    echo "No RSVP found for this event and customer.\n";
}

echo "\n=== POSTMAN API ENDPOINTS ===\n";
echo "Base URL: " . url('/') . "\n";
echo "Headers:\n";
echo "  Authorization: Bearer " . $token . "\n";
echo "  Accept: application/json\n";
echo "  Content-Type: application/json\n\n";

echo "1. RSVP to Event:\n";
echo "  POST /api/customer/event/" . $event->id . "/rsvp\n";
echo "  Body: {\"status\": \"accepted\", \"note\": \"Looking forward to it!\", \"adults_count\": 2, \"children_count\": 1}\n\n";

echo "2. Get RSVP Status:\n";
echo "  GET /api/customer/event/" . $event->id . "/rsvp\n\n";

echo "3. Update RSVP:\n";
echo "  POST /api/customer/event/" . $event->id . "/rsvp\n";
echo "  Body: {\"status\": \"declined\", \"note\": \"Can't make it.\"}\n\n";

echo "Sample test data created successfully!\n";
?>