<?php
session_start();

if (isset($_SESSION['table_id']) && !empty($_SESSION['table_id'])) {
    $tableId = $_SESSION['table_id'];

    $db = new SQLite3('../../../../rodrudee.db');
    if (!$db) {
        die("การเชื่อมต่อล้มเหลว: " . $db->lastErrorMsg());
    }

    $updateTableQuery = "UPDATE tables SET table_status = 'ว่าง' WHERE table_id = '$tableId'";
    $resultTable = $db->exec($updateTableQuery);

    if (!$resultTable) {
        echo $db->lastErrorMsg();
    }

    $updateOrderQuery = "UPDATE orders SET order_status = 'จ่ายแล้ว' WHERE table_id = '$tableId'";
    $resultOrder = $db->exec($updateOrderQuery);

    if (!$resultOrder) {
        echo $db->lastErrorMsg();
    }

    $db->close();
}
?>
