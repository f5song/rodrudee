<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['table_id']) && isset($_POST['order_status'])) {
        $tableId = $_POST['table_id'];
        $orderStatus = $_POST['order_status'];

        $db = new SQLite3('../../rodrudee.db');
        if (!$db) {
            die($db->lastErrorMsg());
        }

        $updateSql = "UPDATE orders SET order_status = :order_status WHERE table_id = :table_id";
        $stmt = $db->prepare($updateSql);
        $stmt->bindParam(':order_status', $orderStatus, SQLITE3_TEXT);
        $stmt->bindParam(':table_id', $tableId, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }

        $db->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
