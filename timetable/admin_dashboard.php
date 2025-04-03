<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Approve Faculty
if (isset($_POST['approve'])) {
    $faculty_id = $_POST['faculty_id'];
    $stmt = $conn->prepare("UPDATE faculty SET status='approved' WHERE faculty_id=?");
    $stmt->execute([$faculty_id]);
}

// Reject Faculty
if (isset($_POST['reject'])) {
    $faculty_id = $_POST['faculty_id'];
    $stmt = $conn->prepare("DELETE FROM faculty WHERE faculty_id=?");
    $stmt->execute([$faculty_id]);
}

// Fetch Pending Faculty Requests
$stmt = $conn->prepare("SELECT * FROM faculty WHERE status='pending'");
$stmt->execute();
$pending_faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Sidebar Styling */
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background-color: #2c3e50;
        color: white;
        padding-top: 20px;
        transition: 0.3s ease-in-out;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        z-index: 100;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sidebar a {
        width: 90%;
        padding: 12px 20px;
        margin: 8px 0;
        text-decoration: none;
        font-size: 18px;
        color: #bdc3c7;
        display: block;
        text-align: center;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .sidebar a:hover {
        color: white;
        background-color: #27ae60;
        transform: scale(.95);
    }

    /* Close Button */
    .sidebar .closebtn {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 30px;
        color: white;
        cursor: pointer;
        transition: color 0.3s ease-in-out;
    }

    .sidebar .closebtn:hover {
        color: #27ae60;
    }

    /* Hamburger Icon Styling */
    .menu-container {
        position: absolute;
        top: 20px;
        left: 20px;
        cursor: pointer;
        z-index: 101;
    }

    .hamburger {
        display: flex;
        flex-direction: column;
        width: 30px;
        justify-content: space-around;
        height: 22px;
    }

    .hamburger div {
        background: #006400;
        height: 4px;
        width: 30px;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    /* Overlay Styles */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10;
    }

        /* Main Content Area Styling */
        .container {
            margin-left: 260px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 60px;
            max-width: 1200px;
            margin: 60px auto;
        }

        h2, h3 {
            color: #2c3e50;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #16a085;
        }

        .button {
            background-color: #27ae60;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin: 10px 0;
        }

        .button:hover {
            background-color: #2ecc71;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f4f6f9;
        }

        /* Form Buttons */
        button {
            padding: 8px 16px;
            border: none;
            color: white;
            background-color: #3498db;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                left: 0;
                width: 200px;
            }

            .menu-container {
                left: 15px;
                top: 15px;
            }

            .hamburger div {
                width: 28px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar">
    <span class="closebtn" onclick="closeNav()">&times;</span>
   <br><br><a href="manage_timetable.php">Timetable Management</a>
<a href="view_timetable.php">View Timetable</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay" onclick="closeNav()"></div>

<!-- Hamburger Menu -->
<div class="menu-container" onclick="openNav()">
    <div class="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

    <!-- Main Content -->
    <div class="container">
        <h2>Admin Dashboard</h2>

        <h3>Pending Faculty Approvals</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
            <?php foreach ($pending_faculty as $faculty) { ?>
            <tr>
                <td><?= htmlspecialchars($faculty['name']) ?></td>
                <td><?= htmlspecialchars($faculty['email']) ?></td>
                <td><?= htmlspecialchars($faculty['department']) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="faculty_id" value="<?= $faculty['faculty_id'] ?>">
                        <button type="submit" name="approve">Approve</button>
                        <button type="submit" name="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>

        <h3>Manage Timetable</h3>
        <a href="manage_timetable.php" class="button">Go to Timetable Management</a>
    </div>

    <script>
        // Open the sidebar
        function openNav() {
            document.getElementById("mySidebar").style.left = "0";
            document.getElementById("overlay").style.display = "block";
        }

        // Close the sidebar
        function closeNav() {
            document.getElementById("mySidebar").style.left = "-250px";
            document.getElementById("overlay").style.display = "none";
        }
    </script>

</body>
</html>
