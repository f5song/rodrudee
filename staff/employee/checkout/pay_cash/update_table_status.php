<?php
session_start();

if (isset($_SESSION['table_id']) && !empty($_SESSION['table_id'])) {
    $tableId = $_SESSION['table_id'];

    $db = new SQLite3('../../../../rodrudee.db');
    if (!$db) {
        die("การเชื่อมต่อล้มเหลว: " . $db->lastErrorMsg());
    }

    $updateQuery = "UPDATE tables SET table_status = 'ว่าง' WHERE table_id = '$tableId'";
    $result = $db->exec($updateQuery);

    if (!$result) {
        echo $db->lastErrorMsg();
    }

    $db->close();
}
?>
