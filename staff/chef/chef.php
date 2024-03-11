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
        <div>
            <div class="queue">
                <!-- <div class="queue_frame">
                    <img src="queuewithbg.png"></img>
                    <div class="num_queue">3 คิว</div>
                </div> -->
            </div>
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
                        echo '<label for="option' . $orderId . '" class="status" data-table-id="' . $tableId . '">กำลังทำ</label>';
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '<div class="button-update">';
                    echo '<button>Update</button>';
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var statusLabels = document.querySelectorAll('.status');
            statusLabels.forEach(function(label) {

                label.addEventListener('click', function() {

                    if (this.textContent === 'กำลังทำ') {
                        this.textContent = 'เสร็จสิ้น';
                    } else {
                        this.textContent = 'กำลังทำ';
                    }
                });
            });
        });

        function updateOrders() {
            var selectedTableIds = [];

            var statusLabels = document.querySelectorAll('.status:checked');
            statusLabels.forEach(function(label) {
                var tableId = label.dataset.tableId;
                selectedTableIds.push(tableId);
            });

            fetch('update_script.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        selectedTableIds: selectedTableIds,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data updated successfully:', data);
                    // ทำสิ่งอื่น ๆ ที่คุณต้องการหลังจากการ insert
                })
                .catch(error => {
                    console.error('Error updating data:', error);
                });
        }
    </script>

</body>

</html>