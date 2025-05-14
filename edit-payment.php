<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/unifiedlayout.css">
    <link rel="stylesheet" href="public/css/edit-pages.css">


</head>

<body>
    <?php
    require 'lib/config.php';
    include 'navbars/uppernavbar.php';
    include 'navbars/sidenavbar.php';

    // Initialize payment data with defaults
    $payment = [
        'id' => $_GET['id'] ?? '',
        'name' => '',
        'package' => '',
        'package_price' => 0,
        'payment' => 0
    ];

    // Load payment data if ID exists
    if (isset($_GET['id'])) {
        require 'lib/edit-payment.php';
    }
    ?>

    <div class="title">Edit Member Payment</div>

    <div class="main-content">
        <div class="container mt-5">
            <!-- Error message container -->
            <div id="errorContainer" class="error-message"></div>

            <form method="POST" action="lib/edit-payment.php" id="paymentForm">
                <input type="hidden" name="id" value="<?= htmlspecialchars($payment['id']) ?>">

                <input type="hidden" name="name" value="<?= htmlspecialchars($payment['name']) ?>">

                <input type="hidden" id="package_price" name="package_price"
                    value="<?= htmlspecialchars($payment['package_price']) ?>">

                <div class="mb-3">
                    <label class="form-label">Member</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($payment['name']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Package</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($payment['package']) ?>"
                        readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Package Price</label>
                    <input type="text" id="price" class="form-control"
                        value="<?= number_format($payment['package_price'], 2) ?>$" readonly>
                </div>

                <div class="mb-3">
                    <label for="payment" class="form-label">Payment Amount</label>
                    <input type="number" step="0.01" class="form-control" id="payment" name="payment"
                        value="<?= htmlspecialchars($payment['payment']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remaining Payment</label>
                    <input type="text" class="form-control" id="remaining"
                        value="<?= number_format($payment['package_price'] - $payment['payment'], 2) ?>$" readonly>
                </div>

                <button type="submit" class="update">Update Payment</button>
                <a href="members.php" class="cancel">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentInput = document.getElementById('payment');
            const errorContainer = document.getElementById('errorContainer');
            const form = document.getElementById('paymentForm');
            const packagePrice = parseFloat(document.getElementById('package_price').value);

            // Real-time calculation and validation
            paymentInput.addEventListener('input', function () {
                const payment = parseFloat(this.value) || 0;
                const remaining = packagePrice - payment;

                // Update remaining field
                document.getElementById('remaining').value = remaining.toFixed(2) + '$';

                // Validate payment
                if (payment > packagePrice) {
                    errorContainer.textContent = "Error: Payment cannot exceed package price!";
                    errorContainer.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    errorContainer.style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });

            // Form submission validation
            form.addEventListener('submit', function (e) {
                const payment = parseFloat(paymentInput.value) || 0;

                if (payment > packagePrice) {
                    e.preventDefault();
                    errorContainer.textContent = "Error: Payment cannot exceed package price!";
                    errorContainer.style.display = 'block';
                    paymentInput.classList.add('is-invalid');
                    paymentInput.focus();
                }
            });
        });
    </script>
</body>

</html>