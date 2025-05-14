<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>members page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/main-records.css">

    <link rel="stylesheet" href="public/css/unifiedlayout.css">
</head>

<body>
    <?php require 'lib/config.php' ?>
    <?php include 'navbars/uppernavbar.php' ?>
    <?php include 'navbars/sidenavbar.php' ?>

    <div class="title">
        Members
    </div>

    <div class="main-content">

        <div class="abuttons">

            <a href="registered-members.php">Registered Members</a>
            <a href="register-members.php">Register Members</a>
        </div>

        <div>


            <div id="inputs">
                <input type="text" id="nameSearch" placeholder="Search by name...">
                <input type="text" id="dateSearch" placeholder="Search by date (DD/MM/YY)">
            </div>


            <div id="membersTableContainer">
                <table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date of Registration</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Payment</th>
                            <th>Remaining Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="membersTableBody">
                        <?php
                        try {
                            $sql = "SELECT id,mid, name, package, payment,price, remainingpayment, dor FROM payments ORDER BY dor DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $formattedDate = date('d/m/y', strtotime($row['dor']));
                                    echo "<tr data-searchable-name='" . htmlspecialchars(strtolower($row['name'])) . "'
                                    data-searchable-date='" . htmlspecialchars($formattedDate) . "'> 
                        <td>" . htmlspecialchars($row['mid']) . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['dor']) . "</td>
                        <td>" . htmlspecialchars($row['package']) . "</td>
                        <td>" . htmlspecialchars($row['price']) . "$</td>
                        <td>" . htmlspecialchars($row['payment']) . "$</td>
                        <td> " . htmlspecialchars($row['remainingpayment']) . "$</td>
                        </td>
                    <td class='action-buttons'>
                                                <a href='edit-payment.php?id=" . htmlspecialchars($row['id']) . "' 
                                                   class='btn btn-sm btn-edit' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                                <form method='POST' action='lib/delete-payment.php' class='d-inline'>
                                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                                    <button type='submit' class='btn btn-sm btn-delete' title='Delete'>
                                                        <i class='fas fa-trash-alt'></i>
                                                    </button>
                                                </form>
                                            </td>
                    </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='7' class='text-center py-4'>
                                            <i class='fas fa-info-circle fa-2x text-muted mb-2'></i><br>
                                            No records found
                                        </td>
                                      </tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='6' style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script src="public/js/live-search.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>