<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Promtpay</title>
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

    <content class="qr">
        <img src="../../../asset/QR.png"></img>
        <div class="button-container">
            <button class="button" onclick="completePayment()">ชำระเสร็จสิ้น</button>
        </div>
    </content>

    <script>
        function completePayment() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    console.log(response); 
                    window.location.href = '../search_table/search_table.php';
                }
            };
            xhr.open("GET", "../pay_cash/update_table_status.php", true);
            xhr.send();
        }
    </script>

</body>

</html>
