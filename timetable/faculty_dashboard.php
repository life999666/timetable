<?php
session_start();
include 'db_connect.php';

// Check if faculty is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

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

        h2 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 24px;
            color: #16a085;
            margin-bottom: 15px;
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
    <br><br><a href="faculty_timetable.php">Manage Timetable</a>
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
    <h2>Faculty Dashboard</h2>

    <h3>Manage Timetable</h3>
    <a href="faculty_management.php" class="button">Go to Timetable Management</a>
</div>

<script>
    function openNav() {
        document.getElementById("mySidebar").style.left = "0";
        document.getElementById("overlay").style.display = "block";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.left = "-250px";
        document.getElementById("overlay").style.display = "none";
    }
</script>

</body>
</html>