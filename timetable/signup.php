<?php
include 'db_connect.php';

// Fetch departments from the department table
$dept_stmt = $conn->prepare("SELECT * FROM department");
$dept_stmt->execute();
$departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Prepare SQL query to insert faculty details
    $stmt = $conn->prepare("INSERT INTO faculty (name, email, department, password, status) VALUES (?, ?, ?, ?, 'pending')");
    
    // Execute the query and check for success
    if ($stmt->execute([$name, $email, $department, $password])) {
        echo "<script>alert('Signup successful! Wait for admin approval.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error in signup. Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Signup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome for eye icon -->
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #2c3e50, #27ae60);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Signup Container */
        .signup-container {
            background: rgba(44, 62, 80, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
            min-height: 500px;
        }

        .signup-container h2 {
            margin-bottom: 25px;
            color: #27ae60;
            font-size: 24px;
            font-weight: 600;
        }

        /* Input Fields */
        .signup-container input, .signup-container select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #ecf0f1;
            color: #2c3e50;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* Password Visibility Toggle */
        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            padding-right: 40px; /* Space for the eye icon */
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #2c3e50;
        }

        /* Signup Button */
        .signup-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #27ae60;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .signup-container button:hover {
            background: #219150;
        }

        /* Login Link */
        .signup-container p {
            margin-top: 20px;
            font-size: 14px;
        }

        .signup-container a {
            color: #27ae60;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-container a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Faculty Signup</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>

            <!-- Department Dropdown (Populated from the database) -->
            <select name="department" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept) { ?>
                    <option value="<?= htmlspecialchars($dept['department_name']) ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                <?php } ?>
            </select>

            <!-- Password Input with Eye Icon -->
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fas fa-eye eye-icon" id="eye-icon" onclick="togglePassword()"></i>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>
