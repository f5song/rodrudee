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

    $sql = "SELECT * FROM staff WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    
    $result = $stmt->execute();

    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $hashedPassword = $row["password"];
        $role = $row["role"];

        if (password_verify($password, $hashedPassword)) {
            echo "เข้าสู่ระบบสำเร็จ";

            if ($role === "chef") {
                header("Location: chef_dashboard.php");
                exit;
            } elseif ($role === "employee") {
                header("Location: ../employee/state/state.php");
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

    $db->close();
}
?>
