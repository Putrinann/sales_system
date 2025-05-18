<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="icon" type="image/png" href="img/icon.jpg">

    <style>
    .dashboard-header {
        background-image: url('img/navyWall.jpeg');
        background-size: cover;
        background-position: center;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        border-radius: 12px;
        color: white;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
    }

    .dashboard-header h2 {
        font-size: 3rem;
        font-weight: bold;
    }

    .summary-box h5 {
        color: #555;
    }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="dashboard-header">
            <h2>MEILAN STORE</h2>
        </div>

        <?php
    $total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
    $total_orders = $conn->query("SELECT COUNT(DISTINCT order_id) as total FROM transactions")->fetch_assoc()['total'];
    $total_revenue = $conn->query("SELECT SUM(total_price) as total FROM transactions")->fetch_assoc()['total'] ?? 0;

    $daily_q = $conn->query("SELECT DATE(created_at) as date, SUM(total_price) as revenue 
                             FROM transactions GROUP BY DATE(created_at) ORDER BY date");
    $labels_daily = [];
    $data_daily = [];
    while ($row = $daily_q->fetch_assoc()) {
      $labels_daily[] = $row['date'];
      $data_daily[] = $row['revenue'];
    }

    $weekly_q = $conn->query("SELECT YEAR(created_at) as year, WEEK(created_at) as week, SUM(total_price) as revenue 
                              FROM transactions GROUP BY year, week ORDER BY year, week");
    $labels_weekly = [];
    $data_weekly = [];
    while ($row = $weekly_q->fetch_assoc()) {
      $labels_weekly[] = "Week {$row['week']} - {$row['year']}";
      $data_weekly[] = $row['revenue'];
    }

    $monthly_q = $conn->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as revenue 
                               FROM transactions GROUP BY month ORDER BY month");
    $labels_monthly = [];
    $data_monthly = [];
    while ($row = $monthly_q->fetch_assoc()) {
      $labels_monthly[] = $row['month'];
      $data_monthly[] = $row['revenue'];
    }

    $yearly_q = $conn->query("SELECT YEAR(created_at) as year, SUM(total_price) as revenue 
                              FROM transactions GROUP BY year ORDER BY year");
    $labels_yearly = [];
    $data_yearly = [];
    while ($row = $yearly_q->fetch_assoc()) {
      $labels_yearly[] = $row['year'];
      $data_yearly[] = $row['revenue'];
    }

    $top_products_q = $conn->query("SELECT products.name as name, SUM(transactions.total_price) as revenue 
                                    FROM transactions 
                                    JOIN products ON transactions.product_id = products.id 
                                    GROUP BY products.id, products.name 
                                    ORDER BY revenue DESC LIMIT 3");
    $top_products = [];
    while ($row = $top_products_q->fetch_assoc()) {
      $top_products[] = $row;
    }
  ?>

        <!-- Ringkasan -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-4 text-center">
                <img src="img/shoesRak.jpeg" alt="Rak Sepatu" width="300" height="200" class="rounded shadow">
            </div>
            <div class="col-md-4">
                <div class="card p-4 summary-box">
                    <h5>Total Products</h5>
                    <h3><?= $total_products ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 summary-box">
                    <h5>Total Revenue</h5>
                    <h3>Rp <?= number_format($total_revenue, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="card p-4 my-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i data-lucide="line-chart" style="width: 20px; height: 20px; color: #0d6efd;"></i>
                <h5 class="mb-0">Revenue Insights</h5>
            </div>
            <div class="btn-group mb-3" role="group">
                <button class="btn btn-outline-primary" onclick="updateChart('daily')">Daily</button>
                <button class="btn btn-outline-primary" onclick="updateChart('weekly')">Weekly</button>
                <button class="btn btn-outline-primary" onclick="updateChart('monthly')">Monthly</button>
                <button class="btn btn-outline-primary" onclick="updateChart('yearly')">Yearly</button>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        <!-- Top Products Chart -->
        <div class="card p-4 my-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i data-lucide="flame" style="width: 20px; height: 20px; color: #ff5722;"></i>
                <h5 class="mb-0">Top 3 Products by Revenue</h5>
            </div>

            <canvas id="topProductsChart" height="100"></canvas>
        </div>

        <!-- Report Buttons -->
        <div class="my-4">
            <a href="download_report.php?type=pdf" class="btn btn-danger">Download PDF Report</a>
            <a href="download_report.php?type=excel" class="btn btn-success">Download Excel Report</a>
        </div>
    </div>

    <script>
    const chartData = {
        daily: {
            labels: <?= json_encode($labels_daily) ?>,
            data: <?= json_encode($data_daily) ?>
        },
        weekly: {
            labels: <?= json_encode($labels_weekly) ?>,
            data: <?= json_encode($data_weekly) ?>
        },
        monthly: {
            labels: <?= json_encode($labels_monthly) ?>,
            data: <?= json_encode($data_monthly) ?>
        },
        yearly: {
            labels: <?= json_encode($labels_yearly) ?>,
            data: <?= json_encode($data_yearly) ?>
        }
    };

    const ctx = document.getElementById('revenueChart').getContext('2d');
    let currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.daily.labels,
            datasets: [{
                label: 'Revenue',
                data: chartData.daily.data,
                backgroundColor: 'rgb(10, 69, 99)',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateChart(period) {
        const newData = chartData[period];
        currentChart.data.labels = newData.labels;
        currentChart.data.datasets[0].data = newData.data;
        currentChart.update();
    }

    const topCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($top_products, 'name')) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode(array_column($top_products, 'revenue')) ?>,
                backgroundColor: 'rgb(69, 112, 165)',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    <script>
    lucide.createIcons();
    </script>

</body>

</html>