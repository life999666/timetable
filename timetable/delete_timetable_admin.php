<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$timetable_id = $_GET['id'];

// Fetch the timetable entry
$stmt = $conn->prepare("SELECT * FROM timetable WHERE timetable_id=?");
$stmt->execute([$timetable_id]);
$timetable = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$timetable) {
    die("Timetable Entry Not Found");
}

// Update timetable entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $subject = $_POST['subject'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];

    $stmt = $conn->prepare("UPDATE timetable SET subject=?, day=?, start_time=?, end_time=?, venue=? WHERE timetable_id=?");
    $stmt->execute([$subject, $day, $start_time, $end_time, $venue, $timetable_id]);

    echo "<script>alert('Timetable updated successfully!'); window.location='manage_timetable.php';</script>";
}

// Delete timetable entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM timetable WHERE timetable_id=?");
    $stmt->execute([$timetable_id]);

    echo "<script>alert('Timetable entry deleted successfully!'); window.location='manage_timetable.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Timetable</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 450px;
            text-align: center;
        }
        h2 {
            color: #2E7D32; /* Luxury Green */
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        input, select {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background: #388E3C;
            color: white;
            padding: 12px;
            border: none;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #2E7D32;
        }
        .delete-btn {
            background: #d32f2f;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background: #b71c1c;
        }
        .back-link {
            display: inline-block;
            margin-top: 12px;
            text-decoration: none;
            color: #fff;
            background: #757575;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }
        .back-link:hover {
            background: #616161;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Timetable Entry</h2>
        <form method="POST">
            <input type="text" name="subject" value="<?= htmlspecialchars($timetable['subject']) ?>" required>
            <select name="day" required>
                <option value="Monday" <?= ($timetable['day'] == "Monday") ? "selected" : "" ?>>Monday</option>
                <option value="Tuesday" <?= ($timetable['day'] == "Tuesday") ? "selected" : "" ?>>Tuesday</option>
                <option value="Wednesday" <?= ($timetable['day'] == "Wednesday") ? "selected" : "" ?>>Wednesday</option>
                <option value="Thursday" <?= ($timetable['day'] == "Thursday") ? "selected" : "" ?>>Thursday</option>
                <option value="Friday" <?= ($timetable['day'] == "Friday") ? "selected" : "" ?>>Friday</option>
                <option value="Saturday" <?= ($timetable['day'] == "Saturday") ? "selected" : "" ?>>Saturday</option>
            </select>
            <input type="time" name="start_time" value="<?= htmlspecialchars($timetable['start_time']) ?>" required>
            <input type="time" name="end_time" value="<?= htmlspecialchars($timetable['end_time']) ?>" required>
            <input type="text" name="venue" value="<?= htmlspecialchars($timetable['venue']) ?>" required>
            
            <button type="submit" name="update">Update Entry</button>
            <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this entry?');">Delete Entry</button>
        </form>
        <a href="manage_timetable.php" class="back-link">Back</a>
    </div>
</body>
</html>
