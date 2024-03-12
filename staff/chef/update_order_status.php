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

    // Validate array sizes
    if (count($orderitemIdsArray) !== count($newStatusesArray)) {
        echo json_encode(["success" => false, "error" => "Mismatched array sizes."]);
        exit();
    }

    try {
        $db = new SQLite3('../../rodrudee.db');

        // Begin transaction
        $db->exec('BEGIN');

        $sql = "UPDATE order_item SET status = :status WHERE orderitem_id = :orderitemId";
        $stmt = $db->prepare($sql);

        for ($i = 0; $i < count($orderitemIdsArray); $i++) {
            $orderitemId = $orderitemIdsArray[$i];
            $status = $newStatusesArray[$i];

            $stmt->bindParam(':status', $status, SQLITE3_TEXT);
            $stmt->bindParam(':orderitemId', $orderitemId, SQLITE3_INTEGER);

            $result = $stmt->execute();

            if (!$result) {
                throw new Exception($db->lastErrorMsg());
            }
        }

        // Commit transaction
        $db->exec('COMMIT');
        echo json_encode(["success" => true]);

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $db->exec('ROLLBACK');
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    } finally {
        // Close the database connection
        $db->close();
    }

} else {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

?>
