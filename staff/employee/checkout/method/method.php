<?php
session_start();
$tableId = '';

if (isset($_SESSION['table_id']) && !empty($_SESSION['table_id'])) {
    $tableId = $_SESSION['table_id'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Payment Method</title>

    <script>
        function redirectToMethodPage() {
            window.location.href = 'promtpay.html';
        }
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
                <div class="queue_frame">
                    <img src="../../../asset/queuewithbg.png"></img>
                    <div class="num_queue">3 คิว</div>
                </div>
            </div>
            <div class="yellow-bar"></div>
        </div>

        <div class="option_container">
            <div class="option-frame">
                <div class="option">
                    <img src="../../../asset/cooking.png"></img>
                    <div class="option-name" id="status">เช็คสถานะอาหาร</div>
                </div>
            </div>

            <div class="option-frame">
                <div class="option">
                    <img src="../../../asset/bill.png"></img>
                    <div class="option-name" id="payment">หน้าชำระเงิน</div>
                </div>
            </div>
        </div>
        </div>

    </top>

    <content>
        <div class="table-information" style="overflow-x:auto;">
            <div class="table-num">
                โต๊ะ 7
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
                
                <div class="topic" id="table-order">
                    <div class="each-order">
                        <p>1.</p>
                    </div>
                    <div class="each-order">
                        <p>ข้าวเหนียว</p>
                    </div>
                    <div class="each-order">
                        <p>2</p>
                    </div>
                    <div class="each-order">
                        <div class="price"> ฿80.00 </div>
                    </div>
                </div>
                <div class="line-under-table-order"></div>

            </div>
        </div>

        <div class="total">รวม ฿80.00 บาท</div>
        <br>
        <br>
    </content>

    <pay-method>
        <div class="button-container">
            <button class="button" onclick="redirectToPaySuccessPage()">จ่ายด้วยเงินสด</button>
            <button class="button" onclick="redirectToMethodPage()">โอนจ่าย</button>
        </div>
        <br>
    </pay-method>
</body>

</html>
