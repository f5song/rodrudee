<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

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


    $sql = "SELECT * FROM staff WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];
        $role = $row["role"];

        if (password_verify($password, $hashedPassword)) {
            echo "เข้าสู่ระบบสำเร็จ";

            if ($role === "chef") {
                header("Location: chef_dashboard.php");
                exit;
            } elseif ($role === "employee") {
                header("Location: employee_dashboard.php");
                exit;
            } else {
                echo "ไม่พบบทบาทที่ถูกกำหนด";
            }
        } else {
            echo "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        echo "ไม่พบข้อมูลผู้ใช้";
    }

    $conn->close();
}
