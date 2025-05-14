<?php
require 'config.php';

function verifyCredentials($username, $password)
{
    global $conn;

    try {
        // 1. Find user by username or email
        $stmt = $conn->prepare("
            SELECT id, username, password_hash, is_active 
            FROM users 
            WHERE username = :username OR email = :username
            LIMIT 1
        ");

        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Verify user exists and is active
        if (!$user || !$user['is_active']) {
            return [
                'success' => false,
                'message' => 'Invalid credentials or account inactive'
            ];
        }

        // 3. Verify password against stored hash
        if (password_verify($password, $user['password_hash'])) {
            // 4. Update last login on success
            $updateStmt = $conn->prepare("
                UPDATE users 
                SET last_login = NOW() 
                WHERE id = :id
            ");
            $updateStmt->execute([':id' => $user['id']]);

            return [
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Database error occurred'
        ];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = verifyCredentials($username, $password);

    if ($result['success']) {
        // Start session, set cookies, etc.
        session_start();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];

        header("Location: ../dashboard.php");
        exit();
    } else {
        $errorMessage = $result['message'];
    }
}
?>