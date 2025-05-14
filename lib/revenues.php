<?php
require 'config.php';

// Initialize totals
$totalPrice = 0.00;
$totalPayment = 0.00;
$filteredPrice = 0.00;
$filteredPayment = 0.00;
$chartData = [];
$tableData = [];

try {
    // Get sum of payments from financialreports table
    $stmt = $conn->query("SELECT SUM(price) as realrevenue FROM financialreports");
    $stmt1 = $conn->query("SELECT SUM(payment) as actualrevenue FROM financialreports");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $totalPrice = $result['realrevenue'] ?? 0.00;
    $totalPayment = $result1['actualrevenue'] ?? 0.00;

    // Revenue calculation for a specific date if provided
    $filteredPrice = $totalPrice;
    $filteredPayment = $totalPayment;

    if (isset($_GET['revenue_date']) && $_GET['revenue_date']) {
        $date = $_GET['revenue_date'];
        $stmt = $conn->prepare("SELECT SUM(price) as realrevenue FROM financialreports WHERE DATE(dor) = ?");
        $stmt1 = $conn->prepare("SELECT SUM(payment) as actualrevenue FROM financialreports WHERE DATE(dor) = ?");
        $stmt->execute([$date]);
        $stmt1->execute([$date]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $filteredPrice = $result['realrevenue'] ?? 0.00;
        $filteredPayment = $result1['actualrevenue'] ?? 0.00;
        // Filter chart data for the specific date
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE DATE(dor) = ? GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE DATE(dor) = ? GROUP BY dor ORDER BY date";
        $chartStmt = $conn->prepare($chartQuery);
        $chartStmt->execute([$date, $date]);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($_GET['month']) && $_GET['month'] && isset($_GET['year']) && $_GET['year']) {
        $month = $_GET['month'];
        $year = $_GET['year'];
        $stmt = $conn->prepare("SELECT SUM(price) as realrevenue FROM financialreports WHERE MONTH(dor) = ? AND YEAR(dor) = ?");
        $stmt1 = $conn->prepare("SELECT SUM(payment) as actualrevenue FROM financialreports WHERE MONTH(dor) = ? AND YEAR(dor) = ?");
        $stmt->execute([$month, $year]);
        $stmt1->execute([$month, $year]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $filteredPrice = $result['realrevenue'] ?? 0.00;
        $filteredPayment = $result1['actualrevenue'] ?? 0.00;
        // Filter chart data for the specific month and year
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE MONTH(dor) = ? AND YEAR(dor) = ? GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE MONTH(dor) = ? AND YEAR(dor) = ? GROUP BY dor ORDER BY date";
        $chartStmt = $conn->prepare($chartQuery);
        $chartStmt->execute([$month, $year, $month, $year]);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($_GET['month']) && $_GET['month']) {
        $month = $_GET['month'];
        $stmt = $conn->prepare("SELECT SUM(price) as realrevenue FROM financialreports WHERE MONTH(dor) = ? AND YEAR(dor) = YEAR(CURDATE())");
        $stmt1 = $conn->prepare("SELECT SUM(payment) as actualrevenue FROM financialreports WHERE MONTH(dor) = ? AND YEAR(dor) = YEAR(CURDATE())");
        $stmt->execute([$month]);
        $stmt1->execute([$month]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $filteredPrice = $result['realrevenue'] ?? 0.00;
        $filteredPayment = $result1['actualrevenue'] ?? 0.00;
        // Filter chart data for the specific month
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE MONTH(dor) = ? AND YEAR(dor) = YEAR(CURDATE()) GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE MONTH(dor) = ? AND YEAR(dor) = YEAR(CURDATE()) GROUP BY dor ORDER BY date";
        $chartStmt = $conn->prepare($chartQuery);
        $chartStmt->execute([$month, $month]);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($_GET['year']) && $_GET['year']) {
        $year = $_GET['year'];
        $stmt = $conn->prepare("SELECT SUM(price) as realrevenue FROM financialreports WHERE YEAR(dor) = ?");
        $stmt1 = $conn->prepare("SELECT SUM(payment) as actualrevenue FROM financialreports WHERE YEAR(dor) = ?");
        $stmt->execute([$year]);
        $stmt1->execute([$year]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $filteredPrice = $result['realrevenue'] ?? 0.00;
        $filteredPayment = $result1['actualrevenue'] ?? 0.00;
        // Filter chart data for the specific year
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE YEAR(dor) = ? GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE YEAR(dor) = ? GROUP BY dor ORDER BY date";
        $chartStmt = $conn->prepare($chartQuery);
        $chartStmt->execute([$year, $year]);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($_GET['start_date']) && $_GET['start_date'] && isset($_GET['end_date']) && $_GET['end_date']) {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $stmt = $conn->prepare("SELECT SUM(price) as realrevenue FROM financialreports WHERE dor BETWEEN ? AND ?");
        $stmt1 = $conn->prepare("SELECT SUM(payment) as actualrevenue FROM financialreports WHERE dor BETWEEN ? AND ?");
        $stmt->execute([$start_date, $end_date]);
        $stmt1->execute([$start_date, $end_date]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $filteredPrice = $result['realrevenue'] ?? 0.00;
        $filteredPayment = $result1['actualrevenue'] ?? 0.00;
        // Filter chart data for the date range
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE dor BETWEEN ? AND ? GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE dor BETWEEN ? AND ? GROUP BY dor ORDER BY date";
        $chartStmt = $conn->prepare($chartQuery);
        $chartStmt->execute([$start_date, $end_date, $start_date, $end_date]);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Default chart data for the last 30 days
        $chartQuery = "SELECT 'Regular' as type, dor as date, SUM(payment) as paid FROM payments WHERE dor BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() GROUP BY dor UNION ALL SELECT 'Personal Training' as type, dor as date, SUM(payment) as paid FROM ptpayments WHERE dor BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() GROUP BY dor ORDER BY date";
        $chartStmt = $conn->query($chartQuery);
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Prepare data for JavaScript
    $chartDataJson = json_encode($chartData);

    // Get table data
    $sql = "SELECT id, mid, name, package, price, payment, dor FROM financialreports";
    if (isset($_GET['revenue_date']) && $_GET['revenue_date']) {
        $date = $_GET['revenue_date'];
        $sql .= " WHERE DATE(dor) = ?";
    } elseif (isset($_GET['month']) && $_GET['month'] && isset($_GET['year']) && $_GET['year']) {
        $month = $_GET['month'];
        $year = $_GET['year'];
        $sql .= " WHERE MONTH(dor) = ? AND YEAR(dor) = ?";
    } elseif (isset($_GET['month']) && $_GET['month']) {
        $sql .= " WHERE MONTH(dor) = ? AND YEAR(dor) = YEAR(CURDATE())";
    } elseif (isset($_GET['year']) && $_GET['year']) {
        $sql .= " WHERE YEAR(dor) = ?";
    } elseif (isset($_GET['start_date']) && $_GET['start_date'] && isset($_GET['end_date']) && $_GET['end_date']) {
        $sql .= " WHERE dor BETWEEN ? AND ?";
    }
    $sql .= " ORDER BY dor DESC";
    $stmt = $conn->prepare($sql);
    if (isset($_GET['revenue_date']) && $_GET['revenue_date']) {
        $stmt->execute([$date]);
    } elseif (isset($_GET['month']) && $_GET['month'] && isset($_GET['year']) && $_GET['year']) {
        $stmt->execute([$month, $year]);
    } elseif (isset($_GET['month']) && $_GET['month']) {
        $stmt->execute([$_GET['month']]);
    } elseif (isset($_GET['year']) && $_GET['year']) {
        $stmt->execute([$_GET['year']]);
    } elseif (isset($_GET['start_date']) && $_GET['start_date'] && isset($_GET['end_date']) && $_GET['end_date']) {
        $stmt->execute([$_GET['start_date'], $_GET['end_date']]);
    } else {
        $stmt->execute();
    }
    $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "<!-- Error: " . htmlspecialchars($e->getMessage()) . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Parse the PHP data
            const rawData = <?php echo $chartDataJson; ?>;

            // Group data by date
            const groupedData = {};
            rawData.forEach(row => {
                if (!groupedData[row.date]) {
                    groupedData[row.date] = {
                        date: row.date,
                        regular: 0,
                        pt: 0
                    };
                }
                if (row.type === 'Regular') {
                    groupedData[row.date].regular = parseFloat(row.paid);
                } else {
                    groupedData[row.date].pt = parseFloat(row.paid);
                }
            });

            // Create DataTable
            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('number', 'Regular Payments');
            data.addColumn('number', 'Personal Training Payments');

            // Add rows
            const rows = Object.values(groupedData).map(item => [
                item.date,
                item.regular,
                item.pt
            ]);
            data.addRows(rows);

            // Set chart options
            const options = {
                title: 'Daily Payments (Last 30 Days)',
                chartArea: { width: '70%' },
                hAxis: {
                    title: 'Date',
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {
                    title: 'Amount Paid'
                },
                colors: ['#4285F4', '#EA4335'],
                isStacked: true
            };

            // Draw chart
            const chart = new google.visualization.ColumnChart(document.getElementById('payment_chart_div'));
            chart.draw(data, options);
        }

        // Redraw chart on window resize
        window.addEventListener('resize', drawChart);
    </script>
</head>


</html>