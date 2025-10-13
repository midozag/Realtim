<?php
// Simple test script to debug realtime
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Realtime Debug Test ===\n";

// Test 1: Check configuration
echo "1. Broadcasting config:\n";
echo "   BROADCAST_CONNECTION: " . env('BROADCAST_CONNECTION') . "\n";
echo "   REVERB_HOST: " . env('REVERB_HOST') . "\n";
echo "   REVERB_PORT: " . env('REVERB_PORT') . "\n";
echo "   REVERB_APP_KEY: " . env('REVERB_APP_KEY') . "\n";
echo "   REVERB_APP_SECRET: " . env('REVERB_APP_SECRET') . "\n\n";

// Test 2: Check broadcaster
echo "2. Broadcaster instance:\n";
$broadcaster = app('Illuminate\Broadcasting\BroadcastManager')->connection();
echo "   Class: " . get_class($broadcaster) . "\n\n";

// Test 3: Try to broadcast simple event
echo "3. Broadcasting test event...\n";
try {
    broadcast(new App\Events\TaskStatusUpdated(3, ['debug' => true]));
    echo "   ✅ Event dispatched successfully\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";