<?php
// Include the database configuration file
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the member ID from the form
    $id = $_POST['id'];
    echo "Deleting potential with ID: $id<br>";

    try {
        // Prepare the SQL query to delete the member
        $sql = "DELETE FROM potentials WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            // Redirect back to the members page
            header("Location: ../potentials.php");
            exit();
        } else {
            echo "No member found with the specified ID.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    header("Location: ../potentials.php");
    exit();
} else {
    // If the request method is not POST, redirect to potentials.php
    header("Location: potentials.php"); // Update the path if needed
    exit();
}

?>