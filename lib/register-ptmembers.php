<?php
require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data

    $memberId = $_POST['member_id'] ?? '';
    $name = $_POST['member'] ?? '';
    $phonenumber = $_POST['phonenumber'] ?? '';
    $trainer = $_POST['trainer'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $ptpackage = $_POST['package'] ?? '';
    $paymentAmount = $_POST['payment'] ?? 0;
    $dor = date("Y-m-d");

    try {
        // Begin transaction
        $conn->beginTransaction();

        // 1. Get package price
        $priceStmt = $conn->prepare("SELECT price FROM ptpackages WHERE name = :ptpackage");
        $priceStmt->execute([':ptpackage' => $ptpackage]);
        $packageData = $priceStmt->fetch();
        $packagePrice = $packageData['price'];

        // 2. Insert into payments table
        $paymentSql = "INSERT INTO ptpayments (mid,name,trainer, package, price, payment, remainingpayment, dor)
                      VALUES (:mid,:name,:trainer, :ptpackage, :price, :payment, :remainingpayment, :dor)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':mid' => $memberId,
            ':name' => $name,
            ':trainer' => $trainer,
            ':ptpackage' => $ptpackage,
            ':price' => $packagePrice,
            ':payment' => $paymentAmount,
            ':remainingpayment' => ($packagePrice - $paymentAmount),
            ':dor' => $dor
        ]);

        //3. Get Last Inserted Payment Id
        $newPaymentId = $conn->lastInsertId();

        $pidStmt = $conn->prepare("SELECT id FROM ptpayments WHERE id = :pid");
        $pidStmt->execute([':pid' => $newPaymentId]);
        $pidData = $pidStmt->fetch();
        $pidId = $pidData['id'];



        // 4. Insert into financialreports
        $paymentSql = "INSERT INTO financialreports (pid,mid,name, package, price, payment, remainingpayment,payment_type, dor)
                      VALUES (:pid, :mid,:name, :ptpackage, :price, :payment, :remainingpayment,:payment_type, :dor)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':pid' => $pidId,
            ':mid' => $memberId,
            ':name' => $name,
            ':ptpackage' => $ptpackage,
            ':price' => $packagePrice,
            ':payment' => $paymentAmount,
            ':remainingpayment' => ($packagePrice - $paymentAmount),
            ':payment_type' => 'ptpayment',
            ':dor' => $dor
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