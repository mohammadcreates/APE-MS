<?php
require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? '';
    $phonenumber = $_POST['phonenumber'] ?? '';
    $dor = date("Y-m-d");

    try {
        // Begin transaction
        $conn->beginTransaction();

        // 1. Insert into potentials table
        $paymentSql = "INSERT INTO potentials (name, phonenumber, dor)
                      VALUES (:name,:phonenumber , :dor)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':name' => $name,
            ':phonenumber' => $phonenumber,
            ':dor' => $dor
        ]);

        // Commit transaction
        $conn->commit();

        // Success message with timeout redirect
        echo '
         <script>
                setTimeout(function() {
                    window.location.href = "../potentials.php";
                }, 300);
            </script>';
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        echo '<div class="error-message">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>