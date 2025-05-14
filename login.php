<?php
session_start();

require 'lib/config.php'; // Database connection

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, username, password_hash, is_active, role, created_at FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['is_active']) {
                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);

                    // Store user data in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['created_at'] = $user['created_at'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header('Location: admin.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit();
                } else {
                    $error = 'Account is disabled';
                }
            } else {
                $error = 'Invalid username or password';
                // Small delay to prevent brute force
                sleep(1);
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage() . "\nError Code: " . $e->getCode());
            $error = 'Login error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/login.css">

</head>

<body>
    <?php include 'navbars/loginnavbar.php' ?>

    <div class="title">
        Ape Management Systems
    </div>
    <div class="main-content">
        <h3 style="font-weight:bold">Login</h3>


        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div>
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= htmlspecialchars($username) ?>" required autofocus>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" class="login">Login</button>

                <a href="register.php">Create User</a>
            </div>
        </form>

        <div>


        </div>
        <br>
        <br>
        <br>

        <div class="support">Technical Support Available 24/7 Email Us support@ape.com or Call Us For Emergency Only On
            01-222-333</div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

<style>
    .main-content {
        margin-left: 150px;
        padding: 20px;
        padding-top: 60px;


    }

    .title {
        margin-left: 170px;
        font-size: 28px;
        font-weight: bold;
    }

    body {
        font-family: 'Montserrat';

    }
</style>

</html>