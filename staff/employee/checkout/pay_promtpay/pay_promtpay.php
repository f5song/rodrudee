<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Promtpay</title>

    <script>
        function redirectToPaySuccessPage() {
            window.location.href = 'pay_success.html';
        }
    </script>
</head>

<body>

<header>
        <img src="../../../asset/profile.png" alt="profile">
        <span>พนักงาน</span>
    </header>
    <top>
        <div>
            <div class="queue">
            </div>
            <div class="yellow-bar"></div>
        </div>

        <div class="option_container">
            <a href="state.php">
                <div class="option">
                    <img src="../../../asset/cooking.png" alt="cooking">
                    <div class="option-name" id="status">เช็คสถานะอาหาร</div>
                </div>
            </a>
            <a href="../checkout/search_table/search_table.php">
                <div class="option">
                    <img src="../../../asset/bill.png" alt="bill">
                    <div class="option-name" id="payment">หน้าชำระเงิน</div>
                </div>
            </a>
        </div>
    </top>
    <content class="qr">
        <img src="../../../asset/QR.png"></img>
        <div class="button-container">
            <button class="button" onclick="redirectToPaySuccessPage()">ชำระเสร็จสิ้น</button>
        </div>
    </content>

</body>

</html>