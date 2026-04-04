<?php
// Simple PHP script to test the API

function testApi($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data && ($method === 'POST' || $method === 'PUT')) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $headerArray = [];
    foreach ($headers as $key => $value) {
        $headerArray[] = "$key: $value";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'httpCode' => $httpCode,
        'response' => $response
    ];
}

// Test 1: Try to access login with GET (this should fail)
echo "Test 1: Accessing login with GET method (should fail)\n";
$result = testApi('http://127.0.0.1:8000/api/admin/login', 'GET');
echo "HTTP Code: " . $result['httpCode'] . "\n";
echo "Response: " . $result['response'] . "\n\n";

// Test 2: Proper login with POST (you'll need to replace with actual credentials)
echo "Test 2: Proper login with POST method\n";
$loginData = [
    'email' => 'admin@example.com',
    'password' => 'password'
];
$result = testApi('http://127.0.0.1:8000/api/admin/login', 'POST', $loginData, [
    'Content-Type: application/json'
]);
echo "HTTP Code: " . $result['httpCode'] . "\n";
echo "Response: " . $result['response'] . "\n\n";

echo "Note: Replace 'admin@example.com' and 'password' with actual admin credentials.\n";