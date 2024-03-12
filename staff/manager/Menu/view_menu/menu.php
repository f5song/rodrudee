<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <title>ดูเมนู</title>
</head>

<body>
    <header class="manager-header">
        <img src="profile.png" alt="profile">
        <span>ผู้จัดการ</span>
    </header>
    <top>
        <div>
            <div class="queue">
                <div class="queue_frame"></div>
            </div>
        </div>

        <div class="option_container">
            <div class="option-frame" id="dashboard">
                <div class="option">
                    <div class="option-name" id="status">สรุปยอดขาย</div>
                </div>
            </div>

            <div class="option-frame selected" id="menu-modify">
                <div class="option">
                    <div class="option-name" id="payment">แก้ไขเมนู</div>
                </div>
            </div>
        </div>
    </top>

    <div class="content">
        <div class="space"></div>
        <div class="center-content">
            <div class="head-button">
                <button id="addMenuButton" class="button-add">เพิ่มเมนู</button>
            </div>
            <div class="bottom-content">
                <div class="box">

                    <?php
                    class MyDB extends SQLite3
                    {
                        function __construct()
                        {
                            $this->open('../../../../rodrudee.db');
                        }
                    }

                    $db = new MyDB();
                    if (!$db) {
                        echo $db->lastErrorMsg();
                        exit;
                    }

                    $sql = "SELECT * FROM menu";
                    $result = $db->query($sql);

                    if ($result) {
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            echo '<div class="box-test1">';
                            echo '<img src="../../../' . $row["file_path"] . '" alt="" width="200" height="150">';
                            echo '<p class="p-name">' . $row["name"] . '</p>';
                            echo '<p class="p-name">' . $row["price"] . ' บาท' . '</p>';
                            echo '<span>';
                            echo '<a href="../modify_menu/modify.php?id=' . $row["menu_id"] . '"><button class="button-modify">แก้ไข</button></a>';
                            echo '<a href="../delete_menu/delete.php?id=' . $row["menu_id"] . '"><button class="button-delete">ลบเมนู</button></a>';
                            echo '</span>';
                            echo '</div>';
                        }
                    } else {
                        echo "Error fetching menu: " . $db->lastErrorMsg();
                    }

                    $db->close();
                    ?>

                </div>
            </div>
        </div>
        <div class="space"></div>
    </div>

    <script>
        document.getElementById("addMenuButton").addEventListener("click", function() {
            window.location.href = "../add_menu/add.php";
        });
    </script>

    <script>
        document.getElementById("dashboard").addEventListener("click", function() {
            window.location.href = "../../Dashboard/dashboard.php";
        });
    </script>

</body>

</html>
