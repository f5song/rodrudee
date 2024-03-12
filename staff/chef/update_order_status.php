<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderitemIds = filter_input(INPUT_POST, "orderitemIds", FILTER_SANITIZE_STRING);
    $newStatuses = filter_input(INPUT_POST, "newStatuses", FILTER_SANITIZE_STRING);

    if ($orderitemIds === null || $newStatuses === null) {
        echo json_encode(["success" => false, "error" => "Invalid input data."]);
        exit();
    }

    $orderitemIdsArray = explode(',', $orderitemIds);
    $newStatusesArray = explode(',', $newStatuses);

    // ตรวจสอบขนาดของอาร์เรย์ให้มีขนาดเท่ากัน
    if (count($orderitemIdsArray) !== count($newStatusesArray)) {
        echo json_encode(["success" => false, "error" => "Mismatched array sizes."]);
        exit();
    }

    $db = new SQLite3('../../rodrudee.db');
    if (!$db) {
        die("Connection failed: " . $db->lastErrorMsg());
    }

    $sql = "UPDATE order_item SET status = :status WHERE orderitem_id = :orderitemId";
    $stmt = $db->prepare($sql);

    for ($i = 0; $i < count($orderitemIdsArray); $i++) {
        $orderitemId = $orderitemIdsArray[$i];
        $status = $newStatusesArray[$i];

        $stmt->bindParam(':status', $status, SQLITE3_TEXT);
        $stmt->bindParam(':orderitemId', $orderitemId, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if (!$result) {
            echo json_encode(["success" => false, "error" => $db->lastErrorMsg()]);
            $db->close();
            exit();
        }
    }

    echo json_encode(["success" => true]);

    $db->close();
} else {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

?>
