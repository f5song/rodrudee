<?php
session_start();

$db = new SQLite3('../../../../rodrudee.db');
if (!$db) {
    die("Connection failed: " . $db->lastErrorMsg());
}

if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];

    $sql = "SELECT * FROM menu WHERE menu_id = $menu_id";
    $result = $db->query($sql);

    if ($result) {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $name = $row['name'];
        $file_path = $row['file_path'];

        if (isset($_POST['delete'])) {

            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $delete_sql = "DELETE FROM menu WHERE menu_id = $menu_id";

            if ($db->exec($delete_sql)) {

                echo '<script>alert("ลบเมนูอาหารสำเร็จ");</script>';
                echo '<script>window.location.href="../view_menu/menu.php";</script>';

                exit();
            } else {
                echo "Error deleting record: " . $db->lastErrorMsg();
            }
        }
    } else {
        echo "No menu found with the provided ID.";
    }
} else {
    echo "Menu ID is not provided in the URL.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="delete.css">
    <title>ลบเมนู</title>
</head>

<body>
    <header>
        <img src="profile.png" alt="profile">
        <span>ผู้จัดการ</span>
    </header>
    <div class="content">
        <div class="box">
            <div class="tab">
                <p>ลบเมนู</p>
            </div>
            <div class="bottom-content">
                <div class="boxcontent">
                    <div class="headname">
                        <span>ต้องการลบเมนู "<?php echo $name; ?>" ใช่หรือไม่</span>
                    </div>
                </div>
                <div class="box-test1">
                    <img src="<?php echo $file_path; ?>" id="food-selected-img" alt="" width="200" height="150">
                    <p class="p-name"><?php echo $name; ?></p>
                </div>
                <div class="forbutton">
                    <form method="post">
                        <button type="submit" name="delete" class="delete">ลบเมนู</button>
                        <button type="button" class="cancel" onclick="cancelbuttonClick()">ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

        function cancelbuttonClick() {
            window.location.href="../view_menu/menu.php";
        }

    </script>

</body>

</html>
