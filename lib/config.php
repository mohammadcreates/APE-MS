<?php
// config.php
$db_username = "if0_38524076"; // Database username
$db_password = "Zy8L7HCeGVwm"; // Database password
$db_host = "sql111.infinityfree.com"; // Database host
$db_database = "if0_38524076_apedb"; // Database name

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_database", $db_username, $db_password);

    // Set PDO to throw exceptions on errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // For debugging
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}

?>