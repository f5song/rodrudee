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
        $this->open(__DIR__ . '/../../rodrudee.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>cart</title>
</head>

<body>
    <div class="content">
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

        <div class="top-header">
                <div class="promotion-head">
                    <div class="back-button">
                        <img id="back-img" src="asset/Back To.png" alt="Back">
                    </div>
                </div>
                <p>ตระกร้า</p>
            </div>

        </div>
        <form id="order-form" action="process_order.php?table_id=<?php echo $selectedTable; ?>" method="post">

            <div class="white-container">
                <?php
                if (!empty($menu_ids)) {
                    $menu_ids_str = implode(',', $menu_ids);

                    $sql = "SELECT * FROM menu WHERE menu_id IN ($menu_ids_str)";
                    $result = $db->query($sql);

                    if ($result) {
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            $menu_id = $row["menu_id"];
                            $count = $menu_counts[$menu_id] ?? 1;
                            echo '<div class="cart-items" id="cart-item-' . $menu_id . '">';
                            echo '<img class="food-img" src="' . $row["file_path"] . '" alt="' . $row["name"] . '">';
                            echo '<div class="food">';
                            echo '<div class="food-item">' . $row["name"] . '</div>';
                            echo '</div>';
                            echo '<div class="quantity">';
                            echo '<button class="minus" aria-label="Decrease" type="button" data-menu-id="' . $menu_id . '" onclick="decreaseValue(' . $menu_id . ', ' . $count . ')">&minus;</button>';
                            echo '<input type="number" class="input-box" value="' . $count . '" min="0" max="20" name="quantity_' . $menu_id . '">';
                            echo '<button class="plus" aria-label="Increase" type="button" data-menu-id="' . $menu_id . '" onclick="increaseValue(' . $menu_id . ', ' . $count . ')">&plus;</button>';
                            echo '</div>';
                            echo '<div class="price">฿' . $row["price"] . '.-</div>';
                            echo '<div class="close" onclick="removeCartItem(' . $menu_id . ')"><img src="asset/close.png" alt="ปิด"></div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>ไม่มีอาหารในตระกร้า</p>';
                    }

                    $db->close();
                } else {
                    echo '<p>ไม่มีอาหารในตระกร้า</p>';
                }
                ?>
            </div>

            <div class="white-bottom">
                <div class="header-bottom">ยอดรวมทั้งหมด <span id="total-price"> ฿</span></div>
                <button type="submit" class="order" name="order_submit">สั่งอาหาร</button>
            </div>
        </form>
    </div>

</body>
</html>

<script>
    document.getElementById('back-img').addEventListener('click', function() {
        window.location.href = '../order/order.php?table_id=<?php echo $selectedTable; ?>';
    });
    
    document.getElementById('logo').addEventListener('click', function() {
        window.location.href = '../homepage';
    });
</script>
