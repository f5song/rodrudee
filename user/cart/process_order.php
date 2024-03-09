<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_submit'])) {
    $table_id = isset($_GET['table_id']) ? $_GET['table_id'] : '';

    echo "Table ID: $table_id<br>";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rodrudee";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (is_array($_SESSION['selectedMenuIds'])) {
        foreach ($_SESSION['selectedMenuIds'] as $menu_id) {
            $quantity = $_POST['quantity_' . $menu_id];
            echo "Menu ID: $menu_id, Quantity: $quantity<br>";

            // ตรวจสอบว่ามีอยู่แล้วหรือไม่
            $existing_order_sql = "SELECT * FROM orders WHERE table_id = '$table_id' AND menu_id = '$menu_id'";
            $existing_order_result = $conn->query($existing_order_sql);

            if ($existing_order_result->num_rows > 0) {
                echo "Order already exists for Menu ID $menu_id and Table ID $table_id<br>";
            } else {
                // ดำเนินการต่อการแทรก
                $price_result = $conn->query("SELECT price FROM menu WHERE menu_id = $menu_id");
                $price_row = $price_result->fetch_assoc();
                $price = $price_row['price'];

                $order_status = 'ออเดอร์ถูกส่งแล้ว';
                $total_price = $quantity * $price;

                $insert_sql = "INSERT INTO orders (table_id, menu_id, quantity, order_status, order_time, price) 
                    VALUES ('$table_id', '$menu_id', '$quantity', '$order_status', CURRENT_TIMESTAMP, '$total_price')";

                if ($conn->query($insert_sql) !== TRUE) {
                    echo "Error: " . $insert_sql . "<br>" . $conn->error;
                } else {
                    echo "Order added successfully<br>";
                }
            }
        }
    }
    $conn->close();
} else {
    http_response_code(404);
    echo 'Not Found';
}
session_unset();
?>
