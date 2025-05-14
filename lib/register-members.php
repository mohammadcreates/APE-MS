<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $memberId = $_POST['member_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $phonenumber = $_POST['phonenumber'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $package = $_POST['package'] ?? '';
    $paymentAmount = $_POST['payment'] ?? 0;
    $dor = date("Y-m-d");

    try {
        // Begin transaction
        $conn->beginTransaction();

        // 1. Insert into members table
        $memberSql = "INSERT INTO members (name, phonenumber, dob, package, payment, dor) 
                     VALUES (:name, :phonenumber, :dob, :package, :payment, :dor)";
        $memberStmt = $conn->prepare($memberSql);
        $memberStmt->execute([
            ':name' => $name,
            ':phonenumber' => $phonenumber,
            ':dob' => $dob,
            ':package' => $package,
            ':payment' => $paymentAmount,
            ':dor' => $dor
        ]);

        // Get the last inserted member ID
        $memberId = $conn->lastInsertId();

        // 2. Get package price
        $priceStmt = $conn->prepare("SELECT price FROM packages WHERE name = :package");
        $priceStmt->execute([':package' => $package]);
        $packageData = $priceStmt->fetch();
        $packagePrice = $packageData['price'];

        // 3. Insert into payments table with member ID (mid)
        $paymentSql = "INSERT INTO payments (mid, name, package, price, payment, remainingpayment, dor)
                      VALUES (:mid, :name, :package, :price, :payment, :remainingpayment, :dor)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':mid' => $memberId,
            ':name' => $name,
            ':package' => $package,
            ':price' => $packagePrice,
            ':payment' => $paymentAmount,
            ':remainingpayment' => ($packagePrice - $paymentAmount),
            ':dor' => $dor
        ]);

        //4. Get Last Inserted Payment Id
        $newPaymentId = $conn->lastInsertId();

        $pidStmt = $conn->prepare("SELECT id FROM payments WHERE id = :pid");
        $pidStmt->execute([':pid' => $newPaymentId]);
        $pidData = $pidStmt->fetch();
        $pidId = $pidData['id'];


        // 5. Insert into financialreports (optional - add mid if needed)
        $paymentSql = "INSERT INTO financialreports (pid,mid, name, package, price, payment, remainingpayment,payment_type, dor)
                       VALUES (:pid,:mid, :name, :package, :price, :payment, :remainingpayment, :payment_type,:dor )";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->execute([
            ':pid' => $pidId,
            ':mid' => $memberId,
            ':name' => $name,
            ':package' => $package,
            ':price' => $packagePrice,
            ':payment' => $paymentAmount,
            ':remainingpayment' => ($packagePrice - $paymentAmount),
            ':payment_type' => 'payment',
            ':dor' => $dor

        ]);



        // Commit transaction
        $conn->commit();

        // Success message with timeout redirect
        echo '
         <script>
                setTimeout(function() {
                    window.location.href = "../members.php";
                }, 300);
            </script>';
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        echo '<div class="error-message">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>