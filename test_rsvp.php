<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Create a new Capsule instance
$capsule = new Capsule;

// Configure the database connection
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'lp',
    'username'  => 'root',
    'password' => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();

// Setup the Eloquent ORM
$capsule->bootEloquent();

// Test the RSVP model
try {
    // Try to count RSVPs
    $count = DB::table('event_rsvps')->count();
    echo "Successfully accessed event_rsvps table. Count: " . $count . "\n";
} catch (Exception $e) {
    echo "Error accessing event_rsvps table: " . $e->getMessage() . "\n";
}