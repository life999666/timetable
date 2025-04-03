<?php
$host = "localhost";
$dbname = "timetable_db"; // Your database name
$username = "root"; // Default for WAMP
$password = ""; // Default for WAMP (no password)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
