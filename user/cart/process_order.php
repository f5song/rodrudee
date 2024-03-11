<?php
session_start();

date_default_timezone_set('Asia/Bangkok');

$menu_ids = $_SESSION['selectedMenuIds'] ?? [];
$menu_counts = array_count_values($menu_ids);

$selectedTable = $_SESSION['selectedTable'] ?? '';
$totalPrice = $_SESSION['totalPrice'] ?? 0;
$orderCount = $_SESSION['orderCount'] ?? 0;

echo "Table ID: $selectedTable<br>";

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

    if (is_array($_SESSION['selectedMenuIds'])) {
        $menu_data = [];
        foreach ($_SESSION['selectedMenuIds'] as $menu_id) {
            $quantity = $_POST['quantity_' . $menu_id];

            if ($quantity > 0) { 
                if (!isset($menu_data[$menu_id])) {
                    $menu_data[$menu_id] = [
                        'quantity' => 0,
                        'price' => 0
                    ];
                }

                $menu_data[$menu_id]['quantity'] += $quantity;
            }
        }
        foreach ($menu_data as $menu_id => $data) {
            $quantity = $data['quantity'];
            $price_result = $db->query("SELECT price FROM menu WHERE menu_id = $menu_id");
            $price_row = $price_result->fetchArray(SQLITE3_ASSOC);
            $price = $price_row['price'];

            $order_status = 'รับออเดอร์';
            $pay_status = 'ยังไม่จ่าย';
            $total_price = $quantity * $price;

            $insert_sql = "INSERT INTO orders (table_id, menu_id, quantity, order_status, order_time, price, pay_status) 
                            VALUES ('$selectedTable', '$menu_id', '$quantity', '$order_status', datetime('now', 'localtime'), '$total_price', '$pay_status')";

            if ($db->exec($insert_sql) !== TRUE) {
                echo "Error inserting order: " . $db->lastErrorMsg();
            } else {
                echo "Order added successfully<br>";
            }
        }
    }

    $db->close();

    header("Location: ../foodlist/foodlist.php?table_id=$selectedTable");
    exit;
} else {
    http_response_code(404);
    echo 'Not Found';
}
?>
