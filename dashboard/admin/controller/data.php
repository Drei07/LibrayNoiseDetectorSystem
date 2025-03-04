<?php

// Directory to store the latest sound sensor data for each device
$dataDir = 'sound_data/';
$timeoutDuration = 60; // 1-minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive data from the sound sensor device
    $data = file_get_contents('php://input');
    $sensorDataArray = json_decode($data, true);

    // Check for DeviceID in the data
    if (isset($sensorDataArray['DeviceID'])) {
        $deviceId = $sensorDataArray['DeviceID'];
        $dataFile = $dataDir . $deviceId . '.json';
        
        // Add a timestamp
        $sensorDataArray['timestamp'] = time();
        
        // Store the updated data
        file_put_contents($dataFile, json_encode($sensorDataArray));
        echo 'Sound Sensor Data received';
    } else {
        echo 'Device ID or Noise Level missing';
    }
} else {
    // Serve the latest sound sensor data for all active devices
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {
        $sensorData = json_decode(file_get_contents($filename), true);
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
