<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];

    $stmt = $conn->prepare("INSERT INTO timetable (department, semester, subject, day, start_time, end_time, venue) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$department, $semester, $subject, $day, $start_time, $end_time, $venue]);

    echo "<script>alert('Timetable added successfully!'); window.location='manage_timetable.php';</script>";
}
?>
