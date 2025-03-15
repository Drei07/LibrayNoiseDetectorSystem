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

    public function sensorThresholds($maxSound, $maxCount, $resetInterval, $cooldownPeriod)
    {
        $stmt = $this->admin->runQuery('SELECT * FROM sensors');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $row["maxSound"] == $maxSound &&
            $row["maxCount"] == $maxCount &&
            $row["resetInterval"] == $resetInterval &&
            $row["cooldownPeriod"] == $cooldownPeriod
        ) {
            $_SESSION['status_title'] = 'Oopss!';
            $_SESSION['status'] = 'No changes have been made to your thresholds.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../thresholds');
            exit;
        }

        $stmt = $this->admin->runQuery('UPDATE sensors SET maxSound=:maxSound, maxCount=:maxCount, resetInterval=:resetInterval, cooldownPeriod=:cooldownPeriod WHERE id=:id');
        $exec = $stmt->execute([
            ":id"             => 1,
            ":maxSound"       => $maxSound,
            ":maxCount"       => $maxCount,
            ":resetInterval"  => $resetInterval,
            ":cooldownPeriod" => $cooldownPeriod,
        ]);

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

    public function storeAndNotify($deviceID, $dbValue)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO noise_alerts_logs (DeviceId, dbValue) VALUES (:deviceID, :dbValue)");
            $stmt->bindParam(":deviceID", $deviceID);
            $stmt->bindParam(":dbValue", $dbValue);
            $stmt->execute();

            $this->sendEmailNotification($deviceID, $dbValue);

            echo json_encode(["success" => true, "message" => "Data stored and email sent."]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    private function sendEmailNotification($deviceID, $dbValue)
    {
        $user = new ADMIN();
        $smtp_email = $user->smtpEmail();
        $smtp_password = $user->smtpPassword();
        $system_name = $user->systemName();

        // Retrieve user data
        $stmt = $user->runQuery("SELECT * FROM users WHERE id=1");
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $email = $user_data['email'];
        $subject = "WARNING: Maximum Sound Level Detected!";

        $message = "
        <html>
        <head>K
            <title>Sound Alert Notification</title>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9; }
                .alert { color: red; font-weight: bold; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='alert'>⚠️ Maximum Sound Level Alert</h2>
                <p><strong>Device:</strong> $deviceID</p>
                <p><strong>Detected Sound Level:</strong> $dbValue dBa</p>
                <p>This sound level has exceeded the maximum safe limit. Immediate action is required to mitigate potential risks.</p>
                <p>Please investigate and take necessary precautions.</p>
                <br>
                <p>Stay safe,</p>
                <p><strong>$system_name</strong></p>
            </div>
            <p class='footer'>This is an automated notification. Please do not reply.</p>
        </body>
        </html>";
        
        // Assuming send_mail is a method to handle sending emails
        $user->send_mail($email, $message, $subject, $smtp_email, $smtp_password, $system_name);
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action']) && $data['action'] === "storeAndNotify") {
        $sensor = new Sensor();
        $sensor->storeAndNotify($data['DeviceID'], $data['dbValue']);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
}

if (isset($_POST['btn-update-thresholds'])) {
    $maxSound = trim($_POST['maxSound']);
    $maxCount = trim($_POST['maxCount']);
    $resetInterval = trim($_POST['resetInterval']);
    $cooldownPeriod = trim($_POST['cooldownPeriod']);

    $sensorData = new Sensor();
    $sensorData->sensorThresholds($maxSound, $maxCount, $resetInterval, $cooldownPeriod);
}
?>
