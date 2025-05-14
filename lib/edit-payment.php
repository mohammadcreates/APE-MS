<?php
require 'config.php'; // Adjust path as needed

// Initialize variables
$payment = null;
$error = null;

// 1. Handle GET request (load payment data)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("SELECT p.*, pk.price as package_price 
                              FROM payments p
                              JOIN packages pk ON p.package = pk.name
                              WHERE p.id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            $error = "Payment record not found";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// 2. Handle POST request (update payment)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        // Validate inputs
        $paymentId = $_POST['id'] ?? null;
        $paymentAmount = (float) ($_POST['payment'] ?? 0);
        $packagePrice = (float) ($_POST['package_price'] ?? 0);

        if ($paymentAmount > $packagePrice) {
            throw new Exception("Payment cannot exceed package price");
        }

        $remaining = $packagePrice - $paymentAmount;

        // Update payment
        $updateStmt = $conn->prepare("UPDATE payments SET 
                                    payment = :payment, 
                                    remainingpayment = :remaining
                                    WHERE id = :id");
        $updateStmt->execute([
            ':payment' => $paymentAmount,
            ':remaining' => $remaining,
            ':id' => $paymentId
        ]);

        // Update financial reports
        $updateFinancial = $conn->prepare("UPDATE financialreports SET 
                                         payment = :payment,
                                         remainingpayment = :remaining
                                         WHERE pid = :pid AND payment_type= :payment_type LIMIT 1");
        $updateFinancial->execute([
            ':payment' => $paymentAmount,
            ':remaining' => $remaining,
            ':pid' => $paymentId,
            ':payment_type' => 'payment'
        ]);

        $conn->commit();

        // Redirect after successful update
        header("Location: ../members.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        $error = $e->getMessage();
    }
}
?>