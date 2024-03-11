<?php
session_start();

date_default_timezone_set('Asia/Bangkok');

$menu_ids = $_SESSION['selectedMenuIds'] ?? [];
$menu_counts = array_count_values($menu_ids);

$selectedTable = $_SESSION['selectedTable'] ?? '';
$totalPrice = $_SESSION['totalPrice'] ?? 0;
$orderCount = $_SESSION['orderCount'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_submit'])) {

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

    if (is_array($_SESSION['selectedMenuIds']) && !empty($_SESSION['selectedMenuIds'])) {
        $order_status = 'รับออเดอร์'; 
        $pay_status = ''; 

        $order_time = date('Y-m-d H:i:s');
        $insert_order_sql = "INSERT INTO orders (table_id, order_status, order_time) 
                            VALUES ('$selectedTable', '$order_status', '$order_time')";

        if ($db->exec($insert_order_sql) !== TRUE) {
            echo "Error inserting order: " . $db->lastErrorMsg();
            $db->close();
            exit;
        }

        $order_id = $db->lastInsertRowID();

        foreach ($_SESSION['selectedMenuIds'] as $menu_id) {
            $quantity = $_POST['quantity_' . $menu_id];

            if ($quantity > 0) {
                $price_result = $db->query("SELECT price FROM menu WHERE menu_id = $menu_id");
                $price_row = $price_result->fetchArray(SQLITE3_ASSOC);
                $price = $price_row['price'];

                $total_price = $quantity * $price;

                $insert_order_item_sql = "INSERT INTO order_item (order_id, menu_id, quantity) 
                                          VALUES ('$order_id', '$menu_id', '$quantity')";

                if ($db->exec($insert_order_item_sql) !== TRUE) {
                    echo "Error inserting order item: " . $db->lastErrorMsg();
                }
            }
        }

        echo "Order added successfully<br>";
    }

    $db->close();

    header("Location: ../foodlist/foodlist.php?table_id=$selectedTable");
    exit;
} else {
    http_response_code(404);
    echo 'Not Found';
}
?>
