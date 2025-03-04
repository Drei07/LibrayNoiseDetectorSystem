<?php

// Directory to store the latest sound sensor data for each device
$dataDir = 'sound_data/';
$timeoutDuration = 60; // 1-minute timeout duration

// Ensure the data directory exists
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the sound sensor device
    $data = file_get_contents('php://input');
    $sensorDataArray = json_decode($data, true);

    if ($sensorDataArray === null) {
        echo json_encode(["error" => "Invalid JSON received"]);
        exit;
    }

    // Validate the required fields
    if (!isset($sensorDataArray['DeviceID']) || !isset($sensorDataArray['dbValue'])) {
        echo json_encode(["error" => "DeviceID or dbValue missing"]);
        exit;
    }

    $deviceId = preg_replace('/[^a-zA-Z0-9_-]/', '', $sensorDataArray['DeviceID']); // Sanitize DeviceID
    $dataFile = $dataDir . $deviceId . '.json';

    // Add a timestamp
    $sensorDataArray['timestamp'] = time();

    // Store the updated data
    if (file_put_contents($dataFile, json_encode($sensorDataArray)) === false) {
    } else {
    }
} else {
    // Serve the latest sound sensor data for all active devices
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {
        $sensorData = json_decode(file_get_contents($filename), true);
        if (!$sensorData) continue;

        $currentTime = time();
        $dataAge = $currentTime - $sensorData['timestamp'];

        if ($dataAge <= $timeoutDuration) {
            $allData[] = $sensorData;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($allData);
}

?>
