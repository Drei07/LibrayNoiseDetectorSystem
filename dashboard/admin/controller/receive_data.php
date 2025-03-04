<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Proxy server URL (Replace with the actual URL where sound data is coming from)
$proxyServerUrl = 'https://silentguard.site/dashboard/admin/controller/soundDevice.php';

// Fetch data from the proxy server
$response = file_get_contents($proxyServerUrl);

if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;

    // Decode the JSON response
    $data = json_decode($response, true);
    error_log(print_r($data, true)); // Debugging output

    // Check if data is in array format and not empty
    if (is_array($data) && !empty($data)) {
        foreach ($data as $sound_data) {
            // Extract data for each sound device
            $device_id = $sound_data['DeviceID'] ?? 'N/A';
            $noise_level = $sound_data['noise_level'] ?? 0;
            $timestamp = date("Y-m-d H:i:s");

            error_log("Sound Device: $device_id | Noise Level: $noise_level | Timestamp: $timestamp");
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid sound data received from the proxy server']);
        error_log("No valid sound data received from proxy server.");
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'DeviceID' => 'N/A',
        'noise_level' => 0,
        'message' => 'Failed to fetch data from proxy server'
    ]);
    error_log("Failed to fetch data from proxy server: " . error_get_last()['message']);
}

?>
