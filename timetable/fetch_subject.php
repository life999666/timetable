<?php
include 'db_connect.php';

if (isset($_POST['department_id']) && isset($_POST['semester'])) {
    $department_id = $_POST['department_id'];
    $semester = $_POST['semester'];

    $stmt = $conn->prepare("SELECT subject_name FROM subject WHERE department_id = ? AND semester = ?");
    $stmt->execute([$department_id, $semester]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Select Subject</option>';
    foreach ($subjects as $subject) {
        echo '<option value="' . htmlspecialchars($subject['subject_name']) . '">' . htmlspecialchars($subject['subject_name']) . '</option>';
    }
}
?>
