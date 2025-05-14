<?php
require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? '';
    $instructor = $_POST['instructor'] ?? '';
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $dor = date("Y-m-d");

    try {
        // Begin transaction
        $conn->beginTransaction();

        // 1. Insert into potentials table
        $paymentSql = "INSERT INTO classes (name, instructor,day,time, dor)
                      VALUES (:name,:instructor,:day,:time, :dor)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':name' => $name,
            ':instructor' => $instructor,
            ':day' => $day,
            ':time' => $time,
            ':dor' => $dor
        ]);

        // Commit transaction
        $conn->commit();

        // Success message with timeout redirect
        echo '
         <script>
                setTimeout(function() {
                    window.location.href = "../classes.php";
                }, 300);
            </script>';
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        echo '<div class="error-message">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>