<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($role === "admin") {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM faculty WHERE email = ? AND status = 'approved'");
    }

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { // Use password_hash() in production
        $_SESSION['user_id'] = $user[$role . '_id'];
        $_SESSION['user_name'] = $user['name'] ?? $user['admin_name'];
        $_SESSION['role'] = $role;

        header("Location: " . ($role === "admin" ? "admin_dashboard.php" : "faculty_dashboard.php"));
        exit();
    } else {
        echo "<script>alert('Invalid login credentials or faculty not approved yet.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #2c3e50, #27ae60);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            margin: 0;
        }
 /* Hamburger Menu */
        .hamburger-menu {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #2c3e50;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        }

        /* Dropdown Menu */
        .menu-content {
            display: none;
            position: absolute;
            top: 60px;
            left: 20px;
            background: rgba(44, 62, 80, 0.9);
            width: 200px;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .menu-content a {
            display: block;
            padding: 12px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }

        .menu-content a:hover {
            background: #27ae60;
        }

        /* Show Menu */
        .menu-content.show {
            display: block;
        }

        .login-container {
            background: rgba(44, 62, 80, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 320px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #27ae60;
        }

        .login-container input,
.login-container select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #ecf0f1;
    color: #2c3e50;
    font-size: 16px;
    box-sizing: border-box;
}

.password-container {
    position: relative;
    width: 100%;
}

.password-container input {
    width: 100%;
    padding-right: 40px; /* Space for the eye icon */
    box-sizing: border-box;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

.toggle-password svg {
    width: 20px;
    height: 20px;
    fill: #2c3e50;
    transition: 0.3s;
}

.toggle-password:hover svg {
    fill: #27ae60;
}


        .login-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #27ae60;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-container button:hover {
            background: #219150;
        }

        .login-container p {
            margin-top: 15px;
        }

        .login-container a {
            color: #27ae60;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="hamburger-menu">
        <span>&#9776; Menu</span>
        <div class="menu-content">
           
            <a href="signup.php">Faculty Signup</a>
            <a href="view_timetable.php">View Timetable</a>
        </div>
    </div>

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="faculty">Faculty</option>
            </select>
            <input type="email" name="email" placeholder="Email" required>

            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span onclick="togglePassword()" id="toggleIcon" class="toggle-password">
                    <svg id="eyeIcon" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 13c-2.48 0-4.5-2.02-4.5-4.5S9.52 8.5 12 8.5s4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5zm0-7c-1.38 0-2.5 1.12-2.5 2.5S10.62 15.5 12 15.5s2.5-1.12 2.5-2.5S13.38 10.5 12 10.5z"/>
                    </svg>
                </span>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>New faculty? <a href="signup.php">Sign up here</a></p>
    </div>

    <script>
 document.querySelector(".hamburger-menu span").addEventListener("click", function() {
            document.querySelector(".menu-content").classList.toggle("show");
        });


        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 13c-2.48 0-4.5-2.02-4.5-4.5S9.52 8.5 12 8.5s4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5zm5.9-4.5c0-3.28-2.62-5.9-5.9-5.9s-5.9 2.62-5.9 5.9S8.72 18.9 12 18.9s5.9-2.62 5.9-5.9z"/>';
            } else {
                passwordField.type = "password";
                eyeIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 13c-2.48 0-4.5-2.02-4.5-4.5S9.52 8.5 12 8.5s4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5z"/>';
            }
        }
    </script>

</body>
</html>
