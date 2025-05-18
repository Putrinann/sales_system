<?php
require 'db.php';
require 'vendor/autoload.php';
use Dompdf\Dompdf;

$type = $_GET['type'] ?? 'pdf';

if ($type === 'pdf') {
    $dompdf = new Dompdf();

    // ========== Bagian 1: Summary per Produk ==========
    $html = '<h2>Sales Report - Product Summary</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr style="background-color: #f2f2f2;">
                    <th>Product Name</th><th>Total Quantity Sold</th><th>Total Revenue</th>
                </tr>';

    $result_summary = $conn->query("SELECT products.name, SUM(transactions.quantity) AS total_qty, SUM(transactions.total_price) AS total_revenue 
                                    FROM transactions 
                                    JOIN products ON transactions.product_id = products.id 
                                    GROUP BY products.id, products.name 
                                    ORDER BY total_revenue DESC");

    while ($row = $result_summary->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['total_qty']}</td>
                    <td>Rp " . number_format($row['total_revenue'], 0, ',', '.') . "</td>
                  </tr>";
    }
    $html .= '</table>';

    // ========== Spacer ==========
    $html .= '<br><br><hr><br>';

    // ========== Bagian 2: History Transaksi ==========
    $html .= '<h2>Sales Report - Transaction History</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr style="background-color: #f2f2f2;">
                    <th>Order ID</th><th>Product Name</th><th>Quantity</th><th>Total Price</th><th>Created At</th>
                </tr>';

    $result_history = $conn->query("SELECT transactions.order_id, products.name, transactions.quantity, transactions.total_price, transactions.created_at 
                                    FROM transactions 
                                    JOIN products ON transactions.product_id = products.id 
                                    ORDER BY transactions.created_at DESC");

    while ($row = $result_history->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>Rp " . number_format($row['total_price'], 0, ',', '.') . "</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
    }
    $html .= '</table>';

    // ========== Generate PDF ==========
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("full_report.pdf");
    exit();
} else {
    echo "Saat ini hanya support PDF gabungan. Gunakan: ?type=pdf";
}
