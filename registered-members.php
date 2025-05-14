<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/form.css">
    <link rel="stylesheet" href="public/css/unifiedlayout.css">

</head>

<body>
    <?php require 'lib/config.php' ?>
    <?php include 'navbars/uppernavbar.php' ?>
    <?php include 'navbars/sidenavbar.php' ?>

    <div class="title">
        Registered Members
    </div>

    <div class="main-content">

        <form action="lib/registered-members.php" method="POST">

            <input type="hidden" name="member_id" id="memberId">
            <?php
            try {
                // Fetch members from database
                $stmt = $conn->query("SELECT id, name, phonenumber FROM members");
                $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Generate member options
                $memberOptions = '';
                foreach ($members as $mbrs) {
                    $memberId = htmlspecialchars($mbrs['id']);
                    $memberName = htmlspecialchars($mbrs['name']);
                    $memberPhonenumber = htmlspecialchars($mbrs['phonenumber']);
                    $memberOptions .= "<option data-id=\"{$memberId}\" value=\"{$memberName}\" data-price=\"{$memberPhonenumber}\">{$memberId} - {$memberName} - {$memberPhonenumber}</option>";
                }

                // Output just the select element with dynamic options
                echo "<select name=\"member\" id=\"member\" class=\"form-control\" required >
            <option value=\"\" selected disabled>-- Choose Member --</option>
            {$memberOptions}
          </select>";

            } catch (PDOException $e) {
                die("Error fetching members: " . $e->getMessage());
            }
            ?>

            <?php
            try {
                // Fetch packages from database
                $stmt = $conn->query("SELECT id, name, price FROM packages");
                $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Generate package options
                $packageOptions = '';
                foreach ($packages as $pkg) {
                    $packageName = htmlspecialchars($pkg['name']);
                    $packagePrice = htmlspecialchars($pkg['price']);
                    $formattedPrice = number_format($pkg['price'], 2);
                    $packageOptions .= "<option value=\"{$packageName}\" data-price=\"{$packagePrice}\">{$packageName} - {$formattedPrice}$</option>";
                }

                // Output just the select element with dynamic options
                echo "<select name=\"package\" id=\"package\" class=\"form-control\" required >
            <option value=\"\" selected disabled>-- Select Package --</option>
            {$packageOptions}
          </select>";

            } catch (PDOException $e) {
                die("Error fetching packages: " . $e->getMessage());
            }
            ?>




            <br>
            <br>
            <br>

            <input type="text" id="price" name="price" readonly>

            <input type="text" id="payment" name="payment" placeholder="Enter Payment Amount">

            <input type="text" id="remaining-payment" name="remaining-payment" readonly placeholder="Remaining Payment">
            <br>
            <button type="submit">Pay</button>
        </form>

    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="public/js/update-price.js"></script>
    <script>
        // Update the hidden member ID when selection changes
        document.getElementById('member').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('memberId').value = selectedOption.getAttribute('data-id');
        });
    </script>

</body>


</html>