<?php
session_start();

$menu_ids = $_SESSION['selectedMenuIds'] ?? [];
$menu_counts = array_count_values($menu_ids);

$selectedTable = $_SESSION['selectedTable'] ?? '';
$totalPrice = $_SESSION['totalPrice'] ?? 0;
$orderCount = $_SESSION['orderCount'] ?? 0;

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

$sql = "SELECT oi.*, o.order_status, m.name as menu_name 
        FROM order_item oi
        JOIN orders o ON oi.order_id = o.order_id
        JOIN menu m ON oi.menu_id = m.menu_id
        WHERE o.table_id = '$selectedTable'";


$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="logo" id="logo">
            <div style="font-size: 5vw;" id="logo-icon">รสฤดี</div>
        </div>
        <nav>
            <a href="../homepage">หน้าหลัก</a>
            <a href="../order/order.php?table_id=<?php echo $selectedTable; ?>">รายการอาหาร</a>
        </nav>
    </header>

    <div class="banner"></div>

    <div class="promotion-head">
        <div class="back-button" onclick="goBack()">
            <img id="back-img"src="image/Back To.png" alt="Back">
        </div>
        <header>รายการอาหาร</header>
    </div>

    <div class="space"></div>

    <div class="table-information" style="overflow-x:auto;">
        <div class="table-num">
            โต๊ะ <?php echo $selectedTable; ?>
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
                    <p>สถานะ</p>
                </div>
            </div>

            <div class="line-under-topic"></div>

            <?php

            $counter = 1;

            if ($result) {

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            ?>
                <div class="topic" id="table-order">
                    <div class="each-order">
                        <p><?php echo $counter; ?></p>
                    </div>
                    <div class="each-order">
                        <p><?php echo $row['menu_name']; ?></p>
                    </div>
                    <div class="each-order">
                        <p><?php echo $row['quantity']; ?></p>
                    </div>
                    <div class="each-order">
                        <div class="status" id="<?php echo ($row['order_status']) ?>">
                            <?php echo $row['status']; ?>
                        </div>
                    </div>
                </div>
                <div class="line-under-table-order"></div>
            <?php
                $counter++;
            }
            if ($counter === 1) {
                echo '<p>No orders for this table.</p>';
            }
        }
            ?>
        </div>
    </div>

    <footer>
        <div class="header" style="font-size: 10vh;">
            รสฤดี
        </div>

        <div class="address">
            <p> 23/5 ถนนพิราภรณ์ ซอยจิตต์ปราณี <br>
                อำเภอชื่นมี จังหวัดกรุงเทพมหานคร 10520</p>
        </div>

        <div class="footer-bottom">

            <div class="contact">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.21778 6.92444C4.49778 9.44 6.56 11.4933 9.07556 12.7822L11.0311 10.8267C11.2711 10.5867 11.6267 10.5067 11.9378 10.6133C12.9333 10.9422 14.0089 11.12 15.1111 11.12C15.6 11.12 16 11.52 16 12.0089V15.1111C16 15.6 15.6 16 15.1111 16C6.76444 16 0 9.23556 0 0.888889C0 0.4 0.4 0 0.888889 0H4C4.48889 0 4.88889 0.4 4.88889 0.888889C4.88889 2 5.06667 3.06667 5.39556 4.06222C5.49333 4.37333 5.42222 4.72 5.17333 4.96889L3.21778 6.92444Z" fill="#FFF8EE" />
                </svg>
                <p> 06507014759 </p>
            </div>
            <div class="time">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 0C4.5 0 0 4.5 0 10C0 15.5 4.5 20 10 20C15.5 20 20 15.5 20 10C20 4.5 15.5 0 10 0ZM14.2 14.2L9 11V5H10.5V10.2L15 12.9L14.2 14.2Z" fill="#FFF8EE" />
                </svg>
                <p> 7:30-20:30 </p>
            </div>

        </div>
    </footer>
</body>

</html>

<script>
    function goBack() {
        window.location.href = '../order/order.php?table_id=<?php echo $selectedTable; ?>';
    }
    window.onload = function() {
        setTimeout(function() {
            <?php
            unset($_SESSION['selectedMenuIds']);
            unset($_SESSION['totalPrice']);
            unset($_SESSION['orderCount']);
            ?>
        }, 5000);

    };

    document.getElementById('logo').addEventListener('click', function() {
        window.location.href = '../homepage';
    });
</script>