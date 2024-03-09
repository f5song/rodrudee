<?php
$table_id = isset($_GET['table_id']) ? $_GET['table_id'] : '';

session_start();

$menu_ids = $_SESSION['selectedMenuIds'] ?? [];
$menu_counts = array_count_values($menu_ids);

print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>ตะกร้า</title>
</head>

<body>
    <div class="content">
        <header>
            <div class="logo">
                <p>รสฤดี</p>
            </div>
            <nav>
                <a href="index.html">หน้าหลัก</a>
                <a href="#">รายการอาหาร</a>
                <a href="#">ข่าวสารและโปรโมชั่น</a>
            </nav>
        </header>

        <div class="container">
            <div class="top-img">
                <img src="asset/head_img.png" alt="รูปภาพหัวเว็บ">
            </div>
            <div class="top-header">
                <p>ตระกร้า</p>
            </div>
        </div>

        <form action="process_order.php?table_id=<?php echo isset($_GET['table']) ? $_GET['table'] : ''; ?>" method="post">
            <div class="white-container">
                <?php
                if (!empty($menu_ids)) {
                    $menu_ids_str = implode(',', $menu_ids);

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "rodrudee";

                    $conn = mysqli_connect($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM menu WHERE menu_id IN ($menu_ids_str)";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $menu_id = $row["menu_id"];
                            $count = $menu_counts[$menu_id] ?? 1;

                            echo '<div class="cart-items" id="cart-item-' . $menu_id . '">';
                            echo '<img class="food-img" src="' . $row["file_path"] . '" alt="' . $row["name"] . '">';
                            echo '<div class="food">';
                            echo '<div class="food-item">' . $row["name"] . '</div>';
                            echo '</div>';
                            echo '<div class="quantity">';
                            echo '<button class="minus" aria-label="Decrease">&minus;</button>';
                            echo '<input type="number" class="input-box" name="quantity_' . $menu_id . '" value="' . $count . '" min="0" max="10">';
                            echo '<button class="plus" aria-label="Increase">&plus;</button>';
                            echo '</div>';
                            echo '<div class="price">฿' . $row["price"] . '</div>';
                            echo '<div class="close" onclick="removeCartItem(' . $menu_id . ')"><img src="asset/close.png" alt="ปิด"></div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>ไม่มีอาหารในตระกร้า</p>';
                    }

                    $conn->close();
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
