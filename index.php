<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
    position: relative; 
    background-color:rgb(162, 157, 142);
    font-family: 'Georgia', serif;
    color: #3e2f23;
    background-image: url('img/autumnWallp.jpeg'); 
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    height: 100vh;
    z-index: 0;
    }

    nav {
    background-color: #6e5849;
    z-index: 3; /* Pastikan navbar paling depan */
    position: relative;
    }

    nav a {
      color: #fff !important;
    }
    .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
    pointer-events: none;
    }
    .container {
    position: relative;
    z-index: 2;
    }
    .main-content {
      position: relative;
      z-index: 2;
    }
    .card {
      border-radius: 15px;
      background-color:rgb(162, 157, 142);
      border: 1px solid #d9c6a5;
      box-shadow: 0 4px 12px rgba(62, 47, 35, 0.2);
    }
    h2{
       color:rgb(242, 211, 194); 
    }
    h3, h5 {
      color:rgb(58, 39, 29);
    }
    .dashboard-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      margin-top: 30px;
    }
    .shoe-image-container {
      flex: 1;
      text-align: center;
    }
    .shoe-image-container img {
      width: 300px;
      height: 200px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    .stats-container {
      flex: 2;
      display: flex;
      gap: 20px;
      justify-content: space-around;
      flex-wrap: wrap;
    }
    @media (max-width: 768px) {
      .dashboard-row {
        flex-direction: column;
      }
      .stats-container {
        flex-direction: column;
        align-items: center;
      }
      .shoe-image-container img {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>
<div class="overlay"></div>

<div class="container main-content mt-5">
  <h2>DASHBOARD</h2>

  <div class="dashboard-row">
    <!-- Left Image -->
    <div class="shoe-image-container">
      <img src="img/shoesRak.jpeg" alt="Shoe Image">
    </div>

    <!-- Right Stats -->
    <div class="stats-container">
      <?php
      $total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
      $total_orders = $conn->query("SELECT COUNT(*) as total FROM transactions")->fetch_assoc()['total'];
      $result_revenue = $conn->query("SELECT SUM(total_price) as total FROM transactions");
      $total_revenue = $result_revenue->fetch_assoc()['total'] ?? 0;

      $revenue_by_date = $conn->query("SELECT DATE(created_at) as date, SUM(total_price) as revenue FROM transactions GROUP BY DATE(created_at) ORDER BY date ASC");
      $labels = [];
      $data = [];
      while ($row = $revenue_by_date->fetch_assoc()) {
        $labels[] = $row['date'];
        $data[] = $row['revenue'];
      }
      ?>
      <div class="card p-4">
        <h5>Total Products</h5>
        <h3><?= $total_products ?></h3>
      </div>
      <div class="card p-4">
        <h5>Total Orders</h5>
        <h3><?= $total_orders ?></h3>
      </div>
      <div class="card p-4">
        <h5>Total Revenue</h5>
        <h3>Rp <?= number_format($total_revenue, 0, ',', '.') ?></h3>
      </div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="row mt-5">
    <div class="col-md-12">
      <div class="card p-4">
        <h5>📈 Revenue Insight</h5>
        <canvas id="revenueChart" height="100"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Revenue per Day',
        data: <?= json_encode($data) ?>,
        borderColor: '#a47148',
        backgroundColor: 'rgba(164, 113, 72, 0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          labels: {
            color: '#3e2f23'
          }
        }
      }
    }
  });
</script>

</body>
</html>
