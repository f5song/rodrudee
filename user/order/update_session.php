<?php
session_start();

if (isset($_POST['selectedMenuIds'])) {
    $selectedMenuIds = json_decode($_POST['selectedMenuIds'], true);

    $_SESSION['selectedMenuIds'] = $selectedMenuIds;
    $orderCount = count($selectedMenuIds);
    $totalPrice = 0;

    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('../../rodrudee.db');
        }
    }

    $db = new MyDB();
    if (!$db) {
        echo $db->lastErrorMsg();
    }


    $menu_ids_str = implode(',', $selectedMenuIds);
    $sql = "SELECT * FROM menu WHERE menu_id IN ($menu_ids_str)";
    $result = $conn->query($sql);

    $menu_counts = array_count_values($selectedMenuIds);

    while ($row = $result->fetch_assoc()) {
        $menu_id = $row["menu_id"];
        $quantity = $menu_counts[$menu_id] ?? 1; // หากไม่มีค่าให้เป็น 1

        $totalPrice += $row["price"] * $quantity;
    }

    $conn->close();

    $_SESSION['orderCount'] = $orderCount;
    $_SESSION['totalPrice'] = $totalPrice;
}

session_write_close();