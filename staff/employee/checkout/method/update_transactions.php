<?php
session_start();
$tableId = '';

if (isset($_SESSION['table_id']) && !empty($_SESSION['table_id'])) {
    $tableId = $_SESSION['table_id'];
}

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../../../../rodrudee.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
}

$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$totalprice = isset($_POST['totalprice']) ? $_POST['totalprice'] : '';

$orderIdQuery = "SELECT order_id FROM orders WHERE table_id = :tableId ORDER BY order_id DESC LIMIT 1";
$orderIdStmt = $db->prepare($orderIdQuery);
$orderIdStmt->bindParam(':tableId', $tableId, SQLITE3_INTEGER);
$orderIdResult = $orderIdStmt->execute();
$row = $orderIdResult->fetchArray(SQLITE3_ASSOC);
$orderId = $row['order_id'];

$updateSql = "UPDATE transactions SET payment_method = :paymentMethod, total = :totalprice WHERE order_id = :orderId";
$updateStmt = $db->prepare($updateSql);
$updateStmt->bindParam(':paymentMethod', $paymentMethod, SQLITE3_TEXT);
$updateStmt->bindParam(':totalprice', $totalprice, SQLITE3_TEXT); // ปรับให้เป็นตามประเภทของคอลัมน์ total
$updateStmt->bindParam(':orderId', $orderId, SQLITE3_INTEGER);

if ($updateStmt->execute()) {
    echo "Transaction updated successfully";
} else {
    echo "Failed to update transaction";
}
?>
