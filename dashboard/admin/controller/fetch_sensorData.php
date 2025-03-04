<?php

include_once __DIR__ . '/../../../database/dbconfig.php';

// Sensor Class to fetch data from the sensor table
class Sensor
{
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    // Method to fetch specified sensor data
    public function getSensorData()
    {
        // Fetch maxSound, resetInterval, and cooldownPeriod from sensors table
        $query = 'SELECT maxSound, resetInterval, cooldownPeriod FROM sensors LIMIT 1';
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $sensorData = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$sensorData) {
                return ["error" => "No sensor data found."];
            }
            // Prepare the selected sensor data
            $result = [
                "maxSound" => $sensorData['maxSound'],
                "resetInterval" => $sensorData['resetInterval'],
                "cooldownPeriod" => $sensorData['cooldownPeriod']
            ];

            return $result;
        } else {
            return ["error" => "Failed to fetch sensor data."];
        }
    }
}

// Instantiate the Database and Sensor classes
try {
    $sensor = new Sensor($db); // Pass the connection to the Sensor class

    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: application/json");

    // Fetch sensor data and return as JSON
    $sensorData = $sensor->getSensorData();
    echo json_encode($sensorData);
} catch (Exception $e) {
    // Handle any unexpected errors
    echo json_encode(["error" => $e->getMessage()]);
}