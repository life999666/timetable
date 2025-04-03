<?php
// Database Connection
$host = "localhost";
$dbname = "timetable_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch Departments
$departments = $conn->query("SELECT DISTINCT department FROM timetable")->fetchAll();

// Fetch Timetable
$timetable = [];
$selected_department = $selected_semester = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);
    $selected_semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_NUMBER_INT);

    if ($selected_department && $selected_semester) {
        $stmt = $conn->prepare("SELECT * FROM timetable WHERE department = ? AND semester = ? ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), start_time");
        $stmt->execute([$selected_department, $selected_semester]);
        $timetable = $stmt->fetchAll();
    }
}

// Days of the Week
$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

// Initialize empty timetable slots
$slots = [];
$times = []; // To keep track of times with classes

// Populate timetable slots with data and track distinct times
foreach ($timetable as $entry) {
    $dayIndex = array_search($entry['day'], $days);

    if ($dayIndex !== false) {
        // Store the slot in the day array
        $slots[$dayIndex][] = $entry;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Timetable</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #2c3e50, #27ae60);
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #27ae60;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .back-btn {
            background: #c0392b;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            left: 20px;
            transition: 0.3s ease-in-out;
        }

        .back-btn:hover {
            background: #a93226;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: inline-block;
            width: 50%;
            min-width: 300px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        select {
            background: #ecf0f1;
            color: #2c3e50;
        }

        button {
            background: #27ae60;
            color: #fff;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: #219150;
        }

        .timetable {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            overflow-x: auto;
        }

        .timetable div {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            font-size: 14px;
            color: #fff;
        }

        .timetable .header {
            background: #27ae60;
            font-weight: bold;
        }

        .timetable .slot {
            background: rgba(0, 0, 0, 0.3);
            font-size: 12px;
        }

        @media screen and (max-width: 768px) {
            form {
                width: 90%;
            }

            .timetable {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <a href="login.php" class="back-btn">⬅ Back to Login</a>

    <h2>View Timetable</h2>

    <form method="POST">
        <label>Select Department:</label>
        <select name="department" required>
            <option value="">Choose</option>
            <?php foreach ($departments as $dept) { ?>
                <option value="<?= htmlspecialchars($dept['department']) ?>" <?= ($selected_department === $dept['department']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['department']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Select Semester:</label>
        <select name="semester" required>
            <option value="">Choose</option>
            <?php for ($i = 1; $i <= 8; $i++) { ?>
                <option value="<?= $i ?>" <?= ($selected_semester == $i) ? 'selected' : '' ?>><?= $i ?>th Semester</option>
            <?php } ?>
        </select>

        <button type="submit">View Timetable</button>
    </form>

    <?php if (!empty($timetable)) { ?>
        <div class="timetable">
            <?php foreach ($days as $day) echo "<div class='header'>$day</div>"; ?>

            <?php
            // Loop through each day and display the classes
            foreach ($days as $dayIndex => $day) {
                echo "<div>";
                // Loop through all classes for that day and display them
                if (!empty($slots[$dayIndex])) {
                    foreach ($slots[$dayIndex] as $class) {
                        echo "<div class='slot'><strong>{$class['subject']}</strong><br>{$class['start_time']} - {$class['end_time']}<br>{$class['venue']}</div>";
                    }
                } else {
                    echo "<div class='slot'><em>No Class</em></div>";
                }
                echo "</div>";
            }
            ?>
        </div>
    <?php } ?>
</body>
</html>
