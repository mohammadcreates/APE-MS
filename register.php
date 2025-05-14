<?php
session_start();
require 'lib/config.php';

$errors = [];
$username = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 4) {
        $errors[] = 'Username must be at least 4 characters';
    }



    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        try {
            // Check if username exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? ");
            $stmt->execute([$username]);

            if ($stmt->fetch()) {
                $errors[] = 'Username already exists';
            } else {
                // Hash password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role, created_at, is_active) VALUES (?, ?, 'user', NOW(), TRUE)");
                $stmt->execute([$username, $password_hash]);

                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: login.php');
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/unifiedlayout.css">
    <link rel="stylesheet" href="public/css/register.css">
</head>

<body>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4 " id="title">Create Account</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">Minimum 8 characters</div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <div id="button" class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="button">Register</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>


</html>