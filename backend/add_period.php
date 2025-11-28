<?php
header('Content-Type: application/json');
// DB connection
include 'config/configure.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $period_name = $_POST['period_name'];
    $start_date  = $_POST['start_date'];
    $end_date    = $_POST['end_date'];

    // Basic validation
    if (!empty($period_name) && !empty($start_date) && !empty($end_date)) {
       // $stmt = $conn->prepare("INSERT INTO review_periods (period_name, start_date, end_date, description, is_active) VALUES (?, ?, ?, ?, 0)");
       $stmt = $conn->query("SELECT id, title, start_date, end_date FROM review_periods ORDER BY start_date DESC");
        $periods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $desc = "Custom user-defined period";
        $stmt->bind_param("ssss", $period_name, $start_date, $end_date, $desc);

        if ($stmt->execute()) {
            echo "Period added successfully!";
            header("Location: mix.php"); // reload modal page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }
}
?>