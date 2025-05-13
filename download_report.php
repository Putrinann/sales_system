<?php
require 'db.php';

require 'vendor/autoload.php'; // HARUS DI ATAS sebelum pakai Dompdf
use Dompdf\Dompdf;

$type = $_GET['type'] ?? 'pdf';

header("Content-Type: application/octet-stream");

if ($type === 'excel') {
    header("Content-Disposition: attachment; filename=report.xlsx");
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    $output = fopen("php://output", "w");
    fputcsv($output, ['Order ID', 'Product Name', 'Quantity', 'Total Price', 'Created At']);

    $result = $conn->query("SELECT transactions.order_id, products.name, transactions.quantity, transactions.total_price, transactions.created_at 
                            FROM transactions 
                            JOIN products ON transactions.product_id = products.id 
                            ORDER BY transactions.created_at DESC");

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();

} elseif ($type === 'pdf') {
    $dompdf = new Dompdf();

    $html = '<h2>Sales Report</h2><table border="1" cellpadding="5" cellspacing="0"><tr>
                <th>Order ID</th><th>Product Name</th><th>Quantity</th><th>Total Price</th><th>Created At</th>
             </tr>';

    $result = $conn->query("SELECT transactions.order_id, products.name, transactions.quantity, transactions.total_price, transactions.created_at 
                            FROM transactions 
                            JOIN products ON transactions.product_id = products.id 
                            ORDER BY transactions.created_at DESC");

    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>Rp " . number_format($row['total_price'], 0, ',', '.') . "</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
    }

    $html .= '</table>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("report.pdf");
    exit();
} else {
    echo "Invalid type!";
}
