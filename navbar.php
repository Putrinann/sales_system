<!-- navbar.php -->
<nav class="navbar navbar-expand-lg" style="background-color: #0d1b2a;">
    <div class="container-fluid py-2">
        <a class="navbar-brand fw-semibold text-white" href="index.php" style="font-size: 1.6rem;">ShoeRevenue</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav gap-2">
                <a class="nav-link text-white" href="index.php">Dashboard</a>
                <a class="nav-link text-white" href="order.php">Order</a>
                <a class="nav-link text-white" href="transaction_history.php">History</a>
                <a class="nav-link text-white" href="product.php">Products</a>
            </div>
        </div>
    </div>
</nav>

<style>
body {
    font-family: 'Segoe UI', 'Roboto', sans-serif;
}

.nav-link {
    padding: 8px 14px;
    border-radius: 8px;
    transition: background-color 0.3s, color 0.3s;
    font-weight: 500;
    font-size: 0.95rem;
}

.nav-link:hover {
    background-color: #1b263b;
    color: #e0e1dd !important;
}

.navbar-toggler {
    background-color: transparent;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.navbar-brand {
    letter-spacing: 0.5px;
}
</style>