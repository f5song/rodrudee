<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Pay_success</title>

  <script>
    function redirectSearchPage() {
      window.location.href = 'search-table.html';
    }

  </script>

</head>

<body>

  <header>
    <img src="../../asset/profile.png" alt="profile">
    <span>พนักงาน</span>
  </header>
  <top>
    <div>
      <div class="queue">
        <div class="queue_frame">
          <img src="../../asset/queuewithbg.png"></img>
          <div class="num_queue">3 คิว</div>
        </div>
      </div>
      <div class="yellow-bar"></div>
    </div>

    <div class="option_container">
      <div class="option-frame">
        <div class="option">
          <img src="../../asset/cooking.png"></img>
          <div class="option-name" id="status">เช็คสถานะอาหาร</div>
        </div>
      </div>

      <div class="option-frame">
        <div class="option">
          <img src="../../asset/bill.png"></img>
          <div class="option-name" id="payment">หน้าชำระเงิน</div>
        </div>
      </div>
    </div>
    </div>
  </top>
  <content>
    <div class="table-information">
      <div class="table-num">
        โต๊ะ 7
      </div>
      <!-- กล่องสีขาว -->
      <div class="white-container">
        <!-- หัวข้อ -->
        <div class="topic" id="header">
          <div class="each-topic">
            <p>ลำดับ</p>
          </div> <!-- each-topic คือทำให้ช่องมันเท่ากันใช้ flex มันจะขยับตาม font -->
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

        <!-- เส่้นขึ้น -->
        <div class="line-under-topic"></div>


        <!--   อาหาร 1 เซ้ต  อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต -->
        <div class="topic" id="table-order">
          <div class="each-order">
            <p>1.</p>
          </div>
          <div class="each-order">
            <p>ส้มตำปูปลาร้า</p>
          </div>
          <div class="each-order">
            <p>1</p>
          </div>
          <div class="each-order">
            <div class="price"> ฿80.00 </div>
          </div>
        </div>

        <!-- เส้นขั้น -->
        <div class="line-under-table-order"></div>
        <!-- อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต อาหาร 1 เซ้ต   -->

        <div class="topic" id="table-order">
          <div class="each-order">
            <p>2.</p>
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

        <div class="topic" id="table-order">
          <div class="each-order">
            <p>3.</p>
          </div>
          <div class="each-order">
            <p>ไก่ย่าง</p>
          </div>
          <div class="each-order">
            <p>3</p>
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
  </content>

  <div class="button-container">
    <button class="button" onclick="redirectSearchPage()">ชำระเสร็จสิ้น</button>
  </div>
</body>

</html>
