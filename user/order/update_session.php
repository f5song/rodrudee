<?php
session_start();

// ตรวจสอบว่ามีข้อมูลที่ต้องการอัปเดต
if (isset($_POST['selectedMenuIds'])) {
    // ดึงค่า selectedMenuIds จาก request
    $selectedMenuIds = json_decode($_POST['selectedMenuIds'], true);

    // อัปเดทค่าใน session
    $_SESSION['selectedMenuIds'] = $selectedMenuIds;

    // คำนวณค่า count-order และ totalprice
    $orderCount = count($selectedMenuIds);
    $totalPrice = 0;

    // เชื่อมต่อกับฐานข้อมูล
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rodrudee";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงข้อมูลเมนูจากฐานข้อมูล
    $menu_ids_str = implode(',', $selectedMenuIds);
    $sql = "SELECT * FROM menu WHERE menu_id IN ($menu_ids_str)";
    $result = $conn->query($sql);

    // สร้าง associative array เพื่อเก็บจำนวนของแต่ละ menu_id
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

// ปิด session หลังจากอัปเดต
session_write_close();
?>
