<?php
session_start();


if (is_array($_SESSION['selectedMenuIds'])) {
    echo "Contents of selectedMenuIds using print_r:<br>";
    print_r($_SESSION['selectedMenuIds']);

    echo "<br><br>";

    echo "Contents of selectedMenuIds using var_dump:<br>";
    var_dump($_SESSION['selectedMenuIds']);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_submit'])) {
    $table_id = isset($_GET['table']) ? $_GET['table'] : '';

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
        
            // Debugging
            echo "Debugging - Quantity from POST: $quantity<br>";
        
            $price_result = $conn->query("SELECT price FROM menu WHERE menu_id = $menu_id");
            $price_row = $price_result->fetch_assoc();
            $price = $price_row['price'];
        
            // Debugging
            echo "Debugging - Price from DB: $price<br>";
        
            $order_status = 'ออเดอร์ถูกส่งแล้ว';
            $total_price = $quantity * $price;
        
            // Debugging
            echo "Debugging - Total Price: $total_price<br>";
        
            $sql = "INSERT INTO orders (table_id, menu_id, quantity, order_status, order_time, price) 
            VALUES ('$table_id', '$menu_id', '$quantity', '$order_status', CURRENT_TIMESTAMP, '$total_price')";
        
            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } else {
                echo "Order added successfully<br>";
            }
        }
        
        
    }
    $conn->close();
} else {
    http_response_code(404);
    echo 'Not Found';
}

?>
