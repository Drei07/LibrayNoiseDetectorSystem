<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/admin-class.php';

class Sensor
{
    private $conn;
    private $admin;

    public function __construct()
    {
        $this->admin = new ADMIN();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }


    public function sensorThresholds($maxSound, $maxCount, $resetInterval, $cooldownPeriod){

        $stmt = $this->admin->runQuery('SELECT * FROM sensors');
        $stmt->execute(array());
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(
            $row["maxSound"] == $maxSound &&
            $row["maxCount"] == $maxCount &&
            $row["resetInterval"] == $resetInterval &&
            $row["cooldownPeriod"] == $cooldownPeriod
        )
        {
            $_SESSION['status_title'] = 'Oopss!';
            $_SESSION['status'] = 'No changes have been made to your thresholds.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;    
    
            header('Location: ../thresholds');
            exit;
        }

        $stmt = $this->admin->runQuery('UPDATE sensors SET maxSound=:maxSound, maxCount=:maxCount, resetInterval=:resetInterval, cooldownPeriod=:cooldownPeriod WHERE id=:id');
        $exec = $stmt->execute(array(

            ":id"            => 1,
            ":maxSound"             => $maxSound,
            ":maxCount"             => $maxCount,
            ":resetInterval"        => $resetInterval,
            ":cooldownPeriod"       => $cooldownPeriod,
        ));
        
        if ($exec) {
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Thresholds successfully updated";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;

                // Log activity
                $activity = "Thresholds successfully updated";
                $user_id = $_SESSION['adminSession'];
                $this->admin->logs($activity, $user_id);
        }

        header('Location: ../thresholds');
        exit;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if(isset($_POST['btn-update-thresholds'])){
    $maxSound = trim($_POST['maxSound']);
    $maxCount = trim($_POST['maxCount']);
    $resetInterval = trim($_POST['resetInterval']);
    $cooldownPeriod = trim($_POST['cooldownPeriod']);


    $sensorData = new Sensor();
    $sensorData->sensorThresholds($maxSound, $maxCount, $resetInterval, $cooldownPeriod);

}
?>