<?php
require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? '';

    try {
        // Begin transaction
        $conn->beginTransaction();

        // 1. Insert into potentials table
        $paymentSql = "INSERT INTO trainers (name)
                      VALUES (:name)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':name' => $name
        ]);

        // Commit transaction
        $conn->commit();

        // Success message with timeout redirect
        echo '
         <script>
                setTimeout(function() {
                    window.location.href = "../ptmembers.php";
                }, 300);
            </script>';
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        echo '<div class="error-message">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>