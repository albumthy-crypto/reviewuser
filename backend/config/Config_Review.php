<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
date_default_timezone_set('Asia/Phnom_Penh');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 't24importdata');
/* Attempt to connect to MySQL database */

$en=0;
$kh=0;


$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
  
}

// Query review periods
$sql = "SELECT id, period_name, start_date, end_date, is_active 
        FROM review_periods 
        ORDER BY start_date DESC";
$result = $conn->query($sql);
?>

<?php $conn->close(); ?>