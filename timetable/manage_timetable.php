<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch All Timetable Entries and Order by Department and Semester
$stmt = $conn->prepare("SELECT * FROM timetable ORDER BY department ASC, semester ASC");
$stmt->execute();
$timetable = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch All Departments
$dept_stmt = $conn->prepare("SELECT DISTINCT department FROM faculty");
$dept_stmt->execute();
$departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Timetable</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #D3D3D3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #2E7D32; /* Luxury Green */
        }
        a {
            text-decoration: none;
            color: #fff;
            background: #2E7D32;
            padding: 10px 15px;
            border-radius: 5px;
            margin-right: 10px;
            display: inline-block;
        }
        a:hover {
            background: #1B5E20;
        }
        .btn-danger {
            background: #D32F2F;
        }
        .btn-danger:hover {
            background: #B71C1C;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        select, input, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #388E3C;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background: #2E7D32;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #e0e0e0;
        }
        .action-links a {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 5px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin - Manage Timetable</h2>
        <a href="admin_dashboard.php">Back to Dashboard</a>
       
        <h3>Add New Timetable Entry</h3>
        <form method="POST" action="add_timetable_admin.php">
            <select name="department" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept) { ?>
                    <option value="<?= htmlspecialchars($dept['department']) ?>"><?= htmlspecialchars($dept['department']) ?></option>
                <?php } ?>
            </select>
            <select name="semester" required>
                <option value="">Select Semester</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
                <option value="3">3rd Semester</option>
                <option value="4">4th Semester</option>
                <option value="5">5th Semester</option>
                <option value="6">6th Semester</option>
                <option value="7">7th Semester</option>
                <option value="8">8th Semester</option>
            </select>
            <input type="text" name="subject" placeholder="Subject" required>
            <select name="day" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
            <input type="time" name="start_time" required>
            <input type="time" name="end_time" required>
            <input type="text" name="venue" placeholder="Venue" required>
            <button type="submit">Add Entry</button>
        </form>

        <h3>Existing Timetable</h3>
        <table>
            <tr>
                <th>Semester</th>
                <th>Department</th>
                <th>Day</th>
                <th>Subject</th>
                <th>Time</th>
                <th>Venue</th>
                <th>Action</th>
            </tr>
            <?php foreach ($timetable as $entry) { ?>
            <tr>
                <td><?= htmlspecialchars($entry['semester']) ?></td>
                <td><?= htmlspecialchars($entry['department']) ?></td>
                <td><?= htmlspecialchars($entry['day']) ?></td>
                <td><?= htmlspecialchars($entry['subject']) ?></td>
                <td><?= htmlspecialchars($entry['start_time'] . " - " . $entry['end_time']) ?></td>
                <td><?= htmlspecialchars($entry['venue']) ?></td>
                <td class="action-links">
                    <a href="edit_timetable_admin.php?id=<?= $entry['timetable_id'] ?>" style="background: #0288D1;">Edit</a>
                    <a href="delete_timetable_admin.php?id=<?= $entry['timetable_id'] ?>" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
