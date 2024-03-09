<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rodrudee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT table_id, table_status FROM tables WHERE table_status = 'ว่าง'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $rows = $result->fetch_all(MYSQLI_ASSOC);

    $selectedRow = $rows[array_rand($rows)];

    $selectedTable = $selectedRow["table_id"];

    $updateSql = "UPDATE tables SET table_status = 'ไม่ว่าง' WHERE table_id = $selectedTable";
    $conn->query($updateSql);

    $totalTables = count($rows);
} else {
    $totalTables = 0;
    $selectedTable = 0;
}

$conn->close();
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

<body>
    <div class="container">
        <header id="navbar" class="navbar">
            <div class="logo">
                <div style="font-size: 5vw;">รสฤดี</div>
            </div>
            <nav>
                <a href="index.php">หน้าหลัก</a>
                <a href="#" onclick="orderNow()">รายการอาหาร</a>
                <a href="order/index.php">ข่าวสารและโปรโมชั่น</a>
            </nav>
        </header>


        <div class="top-content">
            <div class="text-container">
                <div class="image-top">
                    <img src="asset/linethai.png" alt="linethai Foods" class="linethai-image">
                </div>
                <p class="center-text">รสฤดี</p>
            </div>
            <p class="center-text1">ร้านอาหารไทยที่ดีที่สุด</p>
        </div>
        <img src="asset/threetype.png" alt="threetype Foods" class="threetype-image">
    </div>

    <div id="slideshow" class="slideshow-container">
        <div class="mySlides fade">
            <div class="caption-container">
                <img src="asset/1.png" alt="Somtum Big" class="somtumbig-image">
                <div class="caption"></div>
                <button class="order-button" onclick="orderNow()">สั่งเลย</button>
            </div>
        </div>
        <div class="mySlides fade">
            <div class="caption-container">
                <img src="asset/2.png" alt="phamoo Big" class="phamoo-image">
                <div class="caption"></div>
            </div>
            <button class="order-button" onclick="orderNow()">สั่งเลย</button>
        </div>
        <div class="mySlides fade">
            <div class="caption-container">
                <img src="asset/3.png" alt="phadgrapow Big" class="phadgrapow-image">
                <div class="caption"></div>
            </div>
            <button class="order-button" onclick="orderNow()">สั่งเลย</button>
        </div>
        <div class="mySlides fade">
            <div class="caption-container">
                <img src="asset/4.png" alt="padthai Big" class="padthai-image">
                <div class="caption"></div>
            </div>
            <button class="order-button" onclick="orderNow()">สั่งเลย</button>
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
<script>
    function orderNow() {
        var totalTables = <?php echo $totalTables; ?>;
        
        if (totalTables > 0) {
            var selectedTable = <?php echo $selectedTable; ?>;
            sessionStorage.setItem('selectedTable', selectedTable);
            window.location.href = "../order/order.php?table=" + selectedTable;
        } else {
            alert('ขออภัย ไม่มีโต๊ะว่างในขณะนี้');
        }
    }
</script>

</html>