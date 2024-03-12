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

// ทำการ insert ข้อมูลลงในตาราง transactions
$insertSql = "INSERT INTO transactions (order_id, payment_method) VALUES (:orderId, :paymentMethod)";
$stmt = $db->prepare($insertSql);
$stmt->bindParam(':orderId', $tableId, SQLITE3_INTEGER);
$stmt->bindParam(':paymentMethod', $paymentMethod, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo "Transaction inserted successfully";
} else {
    echo "Failed to insert transaction";
}
?>
