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
                        $orderitem_id = $row['orderitem_id'];
                        $tableId = $row['table_id'];
                        $orderId = $row['order_id'];
                        $menuName = $row['menu_name'];
                        $quantity = $row['quantity'];
                        $orderStatus = $row['status'];

                        if ($tableId != $currentTableId) {

                            echo '<div class="box-test1">';
                            echo '<div class="headliner">';
                            echo '<div class="text-button">';
                            echo "<span>โต๊ะ $tableId</span>";
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
                        echo '<label for="option' . $orderId . '" class="status" data-table-id="' . $tableId . '" data-orderitem-id="' . $orderitem_id . '">' . $orderStatus . '</label>';
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var statusLabels = document.querySelectorAll('.status');
            var updateButton = document.getElementById('updateButton');

            var orderitemIds = [];
            var newStatuses = [];

            statusLabels.forEach(function(label) {
                label.addEventListener('click', function() {
                    if (this.textContent === 'กำลังทำ') {
                        this.textContent = 'เสร็จสิ้น';
                        orderitemIds.push(this.dataset.orderitemId);
                        newStatuses.push('เสร็จสิ้น');
                    } else {
                        this.textContent = 'กำลังทำ';
                        orderitemIds.push(this.dataset.orderitemId);
                        newStatuses.push('กำลังทำ');
                    }
                });
            });

            updateButton.addEventListener('click', function() {
                if (orderitemIds.length > 0) {
                    updateOrderStatus(orderitemIds, newStatuses);
                } else {
                    console.error('No order item IDs or new statuses to update.');
                }
            });

            function updateOrderStatus(orderitemIds, newStatuses) {
                var updateXhr = new XMLHttpRequest();
                updateXhr.open('POST', 'update_order_status.php', true);
                updateXhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                updateXhr.onreadystatechange = function() {
                    if (updateXhr.readyState == 4) {
                        if (updateXhr.status == 200) {
                            var response = JSON.parse(updateXhr.responseText);

                            if (response.success) {
                                console.log('Order status updated successfully.');
                            } else {
                                console.error('Error updating order status:', response.error);
                            }
                        } else {
                            console.error('Error updating order status. HTTP status:', updateXhr.status);
                        }
                    }
                };

                var data = 'orderitemIds=' + orderitemIds.join(',') + '&newStatuses=' + newStatuses.join(',');
                updateXhr.send(data);
            }

        });
    </script>

</body>

</html>