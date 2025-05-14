<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        // Start transaction
        $conn->beginTransaction();

        // Delete from financialreports first (to maintain referential integrity)
        $sql1 = "DELETE FROM financialreports WHERE pid = :id";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([':id' => $id]);
        $financialDeleted = $stmt1->rowCount();

        // Then delete from payments
        $sql2 = "DELETE FROM ptpayments WHERE id = :id";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([':id' => $id]);
        $paymentsDeleted = $stmt2->rowCount();

        $conn->commit();

        if ($paymentsDeleted > 0) {
            $_SESSION['message'] = "Payment deleted successfully";
        } else {
            $_SESSION['error'] = "No payment found with ID: $id";
        }

    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: ../ptmembers.php");
    exit();
} else {
    header("Location: ../ptmembers.php");
    exit();
}
?>