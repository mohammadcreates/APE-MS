<?php function getMemberCount($conn)
{
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total_members FROM members");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting member count: " . $e->getMessage());
        return 0; // Return 0 if there's an error
    }
}
function getPotentialCount($conn)
{
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total_potentials FROM potentials");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting member count: " . $e->getMessage());
        return 0; // Return 0 if there's an error
    }
}

function countPtMembers($conn)
{
    try {
        $stmt = $conn->query("SELECT COUNT(DISTINCT mid) AS total FROM ptpayments");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error counting PT members: " . $e->getMessage());
        return 0;
    }
}

?>