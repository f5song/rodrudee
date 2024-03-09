<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ดึงค่า selectedMenuIds จาก request
    $selectedMenuIds = json_decode($_POST['selectedMenuIds'], true);

    // อัปเดตค่าใน session
    $_SESSION['selectedMenuIds'] = $selectedMenuIds;

    // ปิด session หลังจากอัปเดต
    session_write_close();

    // ส่ง response กลับ
    http_response_code(200);
    echo 'Session updated successfully';
} else {
    // ถ้าไม่ใช่ method POST ให้ส่ง response 404 Not Found
    http_response_code(404);
    echo 'Not Found';
}
?>
