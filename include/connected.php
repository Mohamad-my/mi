<?php 
$host = "localhost:3309:3309";
$username = "root";
$password = "";
$dbname = "task";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
} else {
    echo "اتصال ناجح بقاعدة البيانات";
}
?>