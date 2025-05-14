<?php
// Include the database configuration file
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "Deleting user with ID: $id<br>";

    // Get the member ID from the form
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user && $user['role'] === 'admin') {
        // Admin cannot be deleted
        $_SESSION['error'] = "Admin users cannot be deleted";
        header('Location: ../admin.php');
        exit();

    } else {

        try {
            // Prepare the SQL query to delete the member
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                // Redirect back to the members page
                header("Location: ../admin.php");
                exit();
            } else {
                echo "No member found with the specified ID.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        header("Location: ../admin.php");
        exit();
    }
} else {
    // If the request method is not POST, redirect to admin.php
    header("Location: admin.php"); // Update the path if needed
    exit();
}

?>