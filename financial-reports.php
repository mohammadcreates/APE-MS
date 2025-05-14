<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/main-records.css">
    <link rel="stylesheet" href="public/css/unifiedlayout.css">
    <link rel="stylesheet" href="public/css/financial-reports.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body>

    <?php require 'lib/config.php' ?>
    <?php include 'navbars/uppernavbar.php' ?>
    <?php include 'navbars/sidenavbar.php' ?>
    <?php include 'lib/revenues.php' ?>


    <div class="title">
        <h1>Financial Reports</h1>
    </div>

    <div class="main-content">
        <!-- Revenue by Date Form -->
        <div class="mb-3">
            <form method="get">
                <div class="form-group">
                    <label for="revenue_date" class="form-label">Select Date for Revenue:</label>
                    <input type="date" id="revenue_date" name="revenue_date" class="form-control"
                        value="<?= isset($_GET['revenue_date']) ? htmlspecialchars($_GET['revenue_date']) : '' ?>">
                </div>
                <button type="submit" class="btn">Generate</button>

                <div class="form-group">
                    <label for="month" class="form-label">Select Month:</label>
                    <select id="month" name="month" class="form-control">
                        <option value="">Select Month</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>" <?= isset($_GET['month']) && $_GET['month'] == $i ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year" class="form-label">Enter Year:</label>
                    <input type="number" id="year" name="year" class="form-control"
                        value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">
                </div>
                <button type="submit" class="btn btn-secondary">Generate</button>
            </form>
        </div>

        <div class="mb-3">
            <form method="get">
                <div class="form-group">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>">
                </div>
                <button type="submit" class="btn">Generate Range</button>
            </form>
        </div>
        <div id="revenueInputs">
            <label for="">Expected Revenue</label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="">Actual Revenue</label>
            <br>
            <input type="text" disabled readonly value="<?= number_format($filteredPrice, 2) ?>$">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" disabled readonly value="<?= number_format($filteredPayment, 2) ?>$">
        </div>

        <div id="payment_chart_div" style="width: 100%; height: 500px;"></div>

        <div>
            <div id="inputs">
                <input type="text" id="nameSearch" placeholder="Search by name...">
                <input type="text" id="dateSearch" placeholder="Search by date (DD/MM/YY)">
            </div>

            <div id="membersTableContainer">
                <table class="financial-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody">
                        <?php
                        if (count($tableData) > 0) {
                            foreach ($tableData as $row) {
                                $formattedDate = date('d/m/y', strtotime($row['dor']));
                                $isPaid = ($row['payment'] >= $row['price']);
                                $statusClass = $isPaid ? 'paid' : 'pending';
                                $statusText = $isPaid ? 'Paid' : 'Pending';
                                $remaining = $row['price'] - $row['payment'];

                                echo "<tr data-searchable-name='" . htmlspecialchars(strtolower($row['name'])) . "'
                                      data-searchable-date='" . htmlspecialchars($formattedDate) . "'>
                                    <td class='text-center'>" . htmlspecialchars($row['mid']) . "</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td class='text-nowrap'>" . htmlspecialchars($formattedDate) . "</td>
                                    <td>" . htmlspecialchars($row['package']) . "</td>
                                    <td class='text-end'>" . number_format($row['price'], 2) . "$</td>
                                    <td class='text-end'>" . number_format($row['payment'], 2) . "$</td>
                                    <td class='status-cell {$statusClass}'>
                                        {$statusText}
                                        " . ($remaining > 0 ? "<br><small>Remaining: " . number_format($remaining, 2) . "$</small>" : "") . "
                                    </td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr>
                                <td colspan='7' class='text-center py-4'>
                                    <i class='fas fa-info-circle fa-2x text-muted mb-2'></i><br>
                                    No records found
                                </td>
                              </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/live-search.js"></script>
    <script>
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

</body>

</html>