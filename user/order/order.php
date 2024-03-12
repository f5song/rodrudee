<?php
session_start();

if (isset($_GET['table'])) {
    $_SESSION['selectedTable'] = $_GET['table'];
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>รสฤดี</title>
</head>

<header>
        <div class="logo" id="logo">
            <div style="font-size: 5vw;" id="logo-icon">รสฤดี</div>
        </div>
        <nav>
            <a href="../homepage">หน้าหลัก</a>
            <a href="order.php">รายการอาหาร</a>
        </nav>
    </header>

    <div class="banner"></div>
    </div>

    <div class="region-container">
        <div class="region-frame" id="north">
            <input type="radio" name="region" id="northRadio" value="ภาคเหนือ" onchange="filterItems('region', 'ภาคเหนือ')" style="display: none;">
            <div class="region" onclick="document.getElementById('northRadio').click()">
                <img src="asset/north.png"></img>
                <div class="region-name" id="north">ภาคเหนือ</div>
            </div>
        </div>

        <div class="region-frame" id="esan">
            <input type="radio" name="region" id="esanRadio" value="ภาคอีสาน" onchange="filterItems('region', 'ภาคอีสาน')" style="display: none;">
            <div class="region" onclick="document.getElementById('esanRadio').click()">
                <img src="asset/region-esan.png"></img>
                <div class="region-name" id="esan">ภาคอีสาน</div>
            </div>
        </div>

        <div class="region-frame" id="middle">
            <input type="radio" name="region" id="middleRadio" value="ภาคกลาง" onchange="filterItems('region', 'ภาคกลาง')" style="display: none;">
            <div class="region" onclick="document.getElementById('middleRadio').click()">
                <img src="asset/middle.png"></img>
                <div class="region-name" id="middle">ภาคกลาง</div>
            </div>
        </div>

        <div class="region-frame" id="south">
            <input type="radio" name="region" id="southRadio" value="ภาคใต้" onchange="filterItems('region', 'ภาคใต้')" style="display: none;">
            <div class="region" onclick="document.getElementById('southRadio').click()">
                <img src="asset/region-south.png"></img>
                <div class="region-name" id="south">ภาคใต้</div>
            </div>
        </div>
    </div>

    <div class="food-type-container" id="foodType">
        <div class="food-type-flex" id="foodTypes">
            <div class="food-type">
                <input type="checkbox" name="foodType" value="อาหารจานเดียว" onchange="filterItems('type', 'อาหารจานเดียว')">
                <label> อาหารจานเดียว </label>
            </div>

            <div class="food-type">
                <input type="checkbox" name="foodType" value="กับข้าว" onchange="filterItems('type', 'กับข้าว')">
                <label> กับข้าว </label>
            </div>

            <div class="food-type">
                <input type="checkbox" name="foodType" value="ของหวาน" onchange="filterItems('type', 'ของหวาน')">
                <label> ของหวาน </label>
            </div>

            <div class="food-type">
                <input type="checkbox" name="foodType" value="เครื่องดื่ม" onchange="filterItems('type', 'เครื่องดื่ม')">
                <label> น้ำดื่ม </label>
            </div>

            <div class="cart-frame" onclick="goToAnotherPage()">
                <div class="count-order">0</div>
                <div class="cart-price">0.00</div>
            </div>
        </div>
    </div>

    <div class="space"></div>

    <div class="food-list" id="foodList">
        <?php
        $sql = "SELECT * FROM menu";
        $result = $db->query($sql);

        if ($result) {
            echo '<div class="food-list">';
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $menu_id = $row["menu_id"];
                echo '<div class="food-menu-container" data-region="' . $row["region"] . '" data-type="' . $row["category"] . '" data-menu-id="' . $menu_id . '">';
                echo '<img class="food-pic" src="../' . $row["file_path"] . '"><img>';
                echo '<p class="food-name">' . $row["name"] . '</p>';
                echo '<div class="food-price-button" onclick="addToCart(this)">';
                echo '<img id="cart" src="asset/cart.png"><img>';
                echo '<img id="line" src="asset/line.png"><img>';
                echo '<p class="food-price">' . $row["price"] . '.-</p>';
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo "0 results";
        }

        $db->close();

        ?>

</body>

</html>

<script>
    // ตรวจสอบค่า count-order และ totalprice 
    window.addEventListener('DOMContentLoaded', function() {
        var cartPriceElement = document.querySelector('.cart-price');
        var orderCountElement = document.querySelector('.count-order');

        <?php if (isset($_SESSION['totalPrice']) && isset($_SESSION['orderCount'])) : ?>
            totalCartPrice = <?php echo $_SESSION['totalPrice']; ?>;
            orderCount = <?php echo $_SESSION['orderCount']; ?>;

            cartPriceElement.textContent = totalCartPrice.toFixed(2);
            orderCountElement.textContent = orderCount;
        <?php endif; ?>

    });

    var totalCartPrice = 0;
    var orderCount = 0;
    var selectedMenuIds = <?php echo json_encode($_SESSION['selectedMenuIds'] ?? []); ?>;

    if (!Array.isArray(selectedMenuIds)) {
        selectedMenuIds = [];
    }

    function addToCart(button) {
        var foodContainer = button.closest('.food-menu-container');
        var foodName = foodContainer.querySelector('.food-name').textContent;
        var foodPrice = parseFloat(foodContainer.querySelector('.food-price').textContent);
        var menuId = foodContainer.dataset.menuId;

        totalCartPrice += foodPrice;
        orderCount += 1;
        selectedMenuIds.push(menuId);

        var cartPriceElement = document.querySelector('.cart-price');
        var orderCountElement = document.querySelector('.count-order');

        cartPriceElement.textContent = totalCartPrice.toFixed(2);
        orderCountElement.textContent = orderCount;

        updateSession();
    }

    function goToAnotherPage() {
        var selectedTable = <?php echo json_encode($_SESSION['selectedTable'] ?? ''); ?>;
        if (selectedTable) {
            window.location.href = "../cart/cart.php?table=" + selectedTable;
        } 
        
    }

    document.getElementById('logo').addEventListener('click', function() {
        window.location.href = '../homepage';
    });
</script>