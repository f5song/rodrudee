<?php

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


$sql = "SELECT ord.*, o.*, m.name AS menu_name
        FROM order_item o
        JOIN menu m ON o.menu_id = m.menu_id
        JOIN orders ord ON o.order_id = ord.order_id
        ORDER BY ord.table_id, o.orderitem_id;";

$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chef.css">
    <title>ดูเมนู</title>
</head>

<body>
    <header>
        <img src="profile.png" alt="profile">
        <span>เชฟ</span>
    </header>
    <top>

        <div class="queue">
        </div>


        <div class="option_container">
            <div class="option-frame">
                <img src="rectangles.png" alt="">
                <span>ดูคิวทั้งหมด</span>
            </div>
        </div>
    </top>
    <div class="content">
        <div class="space"></div>
        <div class="center-content">
            <div class="box">




                <?php
                if ($result) {
                    $currentTableId = null;
                    $orderCount = 0;

                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        $tableId = $row['table_id'];
                        $orderId = $row['order_id'];
                        $menuName = $row['menu_name'];
                        $quantity = $row['quantity'];
                        $orderStatus = $row['order_status'];

                        if ($tableId != $currentTableId) {
                            if ($currentTableId !== null) {
                                echo '</div>';
                                echo '<div class="button-update">';
                                echo '<button>Update</button>';
                                echo '</div>';
                                echo '</div>';
                            }

                            echo '<div class="box-test1">';
                            echo '<div class="headliner">';
                            echo '<div class="text-button">';
                            echo "<span>โต๊ะ $tableId</span>";
                            echo '<span><button>รับออเดอร์</button></span>';
                            echo '</div>';
                            echo '<div class="line-under-topic"></div>';
                            echo '</div>';
                            echo '<div class="contentinbox">';
                            $currentTableId = $tableId;
                            $orderCount = 0;
                        }

                        $orderCount++;

                        echo '<div class="row">';
                        echo '<div class="left">';
                        echo "<span>$orderCount.</span>";
                        echo "<span>$menuName</span>";
                        echo '<div class="boxnum">';
                        echo "<span>$quantity</span>";
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="right">';
                        echo '<input class="radiocheck" type="radio" id="option1" name="notyet" value="option1" style="display: none;">';
                        echo '<label for="option' . $orderId . '" class="status" data-table-id="' . $tableId . '" data-menu-id="' . $tableId . '">กำลังทำ</label>';
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '<div class="button-update">';
                    echo '<button id="updateButton">Update</button>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "No orders found.";
                }
                $db->close();
                ?>

            </div>
        </div>
        <div class="space"></div>
    </div>
    <?php echo $tableId; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var statusLabels = document.querySelectorAll('.status');

            // ประกาศตัวแปร tableId และ newStatus ไว้นอก loop หรือ event listener เพื่อให้สามารถเข้าถึงจากทุกที่
            var tableId, newStatus;

            statusLabels.forEach(function(label) {
                label.addEventListener('click', function() {
                    if (this.textContent === 'กำลังทำ') {
                        this.textContent = 'เสร็จสิ้น';
                        tableId = this.dataset.tableId;
                        newStatus = 'เสร็จสิ้น';
                        console.log(newStatus, tableId);
                    } else {
                        this.textContent = 'กำลังทำ';
                        tableId = this.dataset.tableId;
                        newStatus = 'กำลังทำ';
                        console.log(newStatus, tableId);
                    }
                });
            });

            // เลือกปุ่ม Update ด้วย ID หรือคลาสและเพิ่มการดักจับคลิก
            var updateButton = document.getElementById('updateButton');
            updateButton.addEventListener('click', function() {
                if (tableId !== undefined && newStatus !== undefined) {
                    updateOrderStatus(tableId, newStatus);
                } else {
                    console.error('Table ID or new status is undefined.');
                }
            });

            function updateOrderStatus(tableId, newStatus) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_order_status.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                console.log('Order status updated successfully.');
                            } else {
                                console.error('Error updating order status:', response.error);
                            }
                        } else {
                            console.error('Error updating order status. HTTP status:', xhr.status);
                        }
                    }
                };

                // ส่งข้อมูลไปยังฟังก์ชัน update_order_status.php
                var data = 'table_id=' + tableId + '&order_status=' + encodeURIComponent(newStatus);
                xhr.send(data);
            }
        });
    </script>

</body>

</html>