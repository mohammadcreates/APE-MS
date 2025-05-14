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
    <link rel="stylesheet" href="public/css/dashboard.css">
    <link rel="stylesheet" href="public/css/unifiedlayout.css">

</head>

<body>
    <?php require 'lib/config.php' ?>
    <?php include 'navbars/uppernavbar.php' ?>
    <?php include 'navbars/sidenavbar.php' ?>
    <?php include 'lib/revenues.php' ?>

    <?php
    require 'lib/get-count.php'; // If you created a separate functions file
    

    // Get member count
    $memberCount = getMemberCount($conn);
    $ptMemberCount = countPtMembers($conn);
    $potentialCount = getPotentialCount($conn);
    ?>

    <div class="title">
        Dashboard
    </div>

    <div class="main-content">
        <div class="row">

            <div class="col-md-6  dashboard-item">
                <img src="public/img/members.png" alt="Image 1" class="img-fluid">
                <div class="stat-number"><?= number_format($memberCount) ?></div>
            </div>

            <div class="col-md-6  dashboard-item">
                <img src="public/img/ptmembers.png" alt="Image 2" class="img-fluid">
                <div class="stat-number"><?= number_format($ptMemberCount) ?></div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6  dashboard-item">
                <img src="public/img/potentials.png" alt="Image 3" class="img-fluid">
                <div class="stat-number"><?= number_format($potentialCount) ?></div>
            </div>

            <div class="col-md-6  dashboard-item">
                <img src="public/img/money.png" alt="Image 4" class="img-fluid">
                <div><?= number_format($totalPayment, 2) ?>$</div>

            </div>
        </div>
    </div>





    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>
<style>
    .main-content {
        margin-left: 200px;
        padding: 20px;
    }

    .title {
        margin-left: 150px;
        font-size: 28px;
        font-weight: bold;
    }

    body {
        font-family: 'Montserrat';
    }
</style>

</html>