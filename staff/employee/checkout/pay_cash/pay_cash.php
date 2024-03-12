<?php
session_start();
$tableId = '';

if (isset($_SESSION['table_id']) && !empty($_SESSION['table_id'])) {
    $tableId = $_SESSION['table_id'];
}

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
}

$sql = "SELECT o.*, m.name as menu_name, oi.*, m.* FROM orders o
        JOIN order_item oi ON o.order_id = oi.order_id
        JOIN menu m ON oi.menu_id = m.menu_id
        WHERE o.table_id = '$tableId'
        ORDER BY o.table_id, o.order_id;";

$tableInfoResult = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Payment Confirmation</title>
</head>

<body>

    <header>
        <img src="../../../asset/profile.png" alt="profile">
        <span>พนักงาน</span>
    </header>

    <top>
        <div>
            <div class="queue"></div>
            <div class="yellow-bar"></div>
        </div>
        <div class="option_container">
            <a href="../../state/state.php">
                <div class="option-frame">
                    <div class="option">
                        <img src="../../../asset/cooking.png">
                        <div class="option-name" id="status">เช็คสถานะอาหาร</div>
                    </div>
                </div>
            </a>
            <a href="../search_table/search_table.php">
                <div class="option-frame">
                    <div class="option">
                        <img src="../../../asset/bill.png">
                        <div class="option-name" id="payment">หน้าชำระเงิน</div>
                    </div>
                </div>
            </a>
        </div>
    </top>

    <content>
        <div class="table-information" style="overflow-x:auto;">
            <div class="table-num">
                โต๊ะ <?php echo $tableId; ?>
            </div>
            <div class="white-container">
                <div class="topic" id="header">
                    <div class="each-topic">
                        <p>ลำดับ</p>
                    </div>
                    <div class="each-topic">
                        <p>รายการอาหาร</p>
                    </div>
                    <div class="each-topic">
                        <p>จำนวน</p>
                    </div>
                    <div class="each-topic">
                        <p>ราคา</p>
                    </div>
                </div>

                <div class="line-under-topic"></div>

                <?php
                if ($tableInfoResult) {
                    $orderCount = 1;
                    $totalPrice = 0;
                    while ($row = $tableInfoResult->fetchArray(SQLITE3_ASSOC)) {
                ?>
                        <div class="topic" id="table-order">
                            <div class="each-order">
                                <p><?php echo $orderCount; ?></p>
                            </div>
                            <div class="each-order">
                                <p><?php echo $row['menu_name']; ?></p>
                            </div>
                            <div class="each-order">
                                <p><?php echo $row['quantity']; ?></p>
                            </div>
                            <div class="each-order">
                                <div class="price"> ฿<?php echo number_format($row['price'] * $row['quantity'], 2); ?></div>
                            </div>
                        </div>
                        <div class="line-under-table-order"></div>
                <?php
                        $totalPrice += $row['price'] * $row['quantity'];
                        $orderCount++;
                    }
                }
                ?>
            </div>
        </div>

        <div class="total">รวม ฿<?php echo number_format($totalPrice, 2); ?> บาท</div>
        <br>
        <br>
    </content>

    <div class="button-container">
        <button class="button" onclick="redirectSearchPage()">ชำระเสร็จสิ้น</button>
    </div>

    <script>
        function redirectSearchPage() {
            window.location.href = '../search_table/search_table.php';
        }
    </script>

</body>

</html>
