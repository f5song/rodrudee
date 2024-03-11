<?php

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../../../rodrudee.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
}

$sql = "SELECT o.*, m.name as menu_name FROM orders o
        JOIN menu m ON o.menu_id = m.menu_id
        ORDER BY o.table_id, o.order_id;";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Check Status</title>
</head>

<body>

    <header>
        <img src="../../asset/profile.png" alt="profile">
        <span>พนักงาน</span>
    </header>
    <top>
        <div>
            <div class="queue">
                <!-- <div class="queue_frame"> -->
                <!-- <img src="../../asset/queuewithbg.png" alt="queue"> -->
                <!-- <div class="num_queue">
                        <?php
                        // if ($result) {
                        //     $numBoxes = $result->num_rows;
                        //     echo "$numBoxes คิว";
                        // } else {
                        //     echo "0 คิว";
                        // }
                        // 
                        ?>
                    </div> -->
                <!-- </div> -->
            </div>
            <div class="yellow-bar"></div>
        </div>

        <div class="option_container">
            <a href="state.php">
                <div class="option">
                    <img src="../../asset/cooking.png" alt="cooking">
                    <div class="option-name" id="status">เช็คสถานะอาหาร</div>
            </a>
        </div>

        <div class="option-frame">
            <a href="../checkout/search_table/search_table.php">
                <div class="option">
                    <img src="../../asset/bill.png" alt="bill">
                    <div class="option-name" id="payment">หน้าชำระเงิน</div>
                </div>
            </a>
        </div>

        </div>
    </top>
    <div class="content">
        <div class="space"></div>
        <div class="center-content">
            <div class="headlinertext">
                <img src="../../asset/do-queue.png" alt="do queue">
                <span>ดูคิวทั้งหมด</span>
            </div>
            <div class="box">

                <?php
                if ($result) {
                    $currentTableId = null;
                    $orderCount = 0;


                    while ($row = $result->fetch_assoc()) {
                        $tableId = $row['table_id'];
                        $orderId = $row['order_id'];
                        $menuName = $row['menu_name'];
                        $quantity = $row['quantity'];
                        $orderStatus = $row['order_status'];


                        if ($tableId != $currentTableId) {
                            if ($currentTableId !== null) {
                                echo '</div>';
                                echo '</div>';
                            }
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

                        // Generate HTML for each row within the same table
                        echo '<div class="row">';
                        echo '<div class="left">';
                        echo "<span>$orderCount</span>";
                        echo "<span>$menuName</span>";
                        echo '<div class="boxnum">';
                        echo "<span>$quantity</span>";
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="right">';
                        echo "<span class='status'>$orderStatus</span>";
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "No orders found.";
                }
                $conn->close();
                ?>

            </div>
        </div>
        <div class="space"></div>
    </div>

</body>

</html>