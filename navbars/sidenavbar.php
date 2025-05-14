<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="public/css/navbars.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-3 col-lg-2 d-md-block sidebar ">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="members.php">
                                Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ptmembers.php">
                                PT Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="potentials.php">
                                Potentials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="classes.php">
                                Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aiplanner/index.php">
                                AI Planner
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="financial-reports.php">
                                Financial Reports
                            </a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">
                                    Admin
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </nav>
        </div>
    </div>
</body>


</html>