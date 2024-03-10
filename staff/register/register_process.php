<?php
// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // รับค่าชื่อจริง, นามสกุล, ชื่อผู้ใช้ และรหัสผ่านจากฟอร์ม
    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["password-confirm"];

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if ($password != $passwordConfirm) {
        echo "รหัสผ่านไม่ตรงกัน";
        exit();
    }

    // ทำการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $usernameDB = "root"; // แทนที่ด้วยชื่อผู้ใช้ฐานข้อมูลของคุณ
    $passwordDB = ""; // แทนที่ด้วยรหัสผ่านฐานข้อมูลของคุณ
    $dbname = "rodrudee"; // แทนที่ด้วยชื่อฐานข้อมูลของคุณ

    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // แฮชรหัสผ่าน
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูล
    $sql = "INSERT INTO staff (first_name, last_name, username, password) VALUES ('$firstName', '$lastName', '$username', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "บันทึกข้อมูลเรียบร้อย";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
