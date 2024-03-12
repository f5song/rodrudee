
<?php
  $con= mysqli_connect("localhost","root","","rodrudee") or die("Error: " . mysqli_error($con));
  date_default_timezone_set('Asia/Bangkok');
?>


<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <title>ผู้จัดการ</title>
  </head>

  <body style="background-color: #FFF8EE;"> 

    <header>
      <manager-header>
        <img src="profile.png" alt="profile">
        <span>ผู้จัดการ</span>
      </manager-header>
    </header>
    <top>
        <div>
            <div class="queue">
                <div class="queue_frame"></div>
            </div>
        </div>

        <div class="option_container">
            <div class="option-frame selected">
                <div class="option">
                    <div class="option-name" id="status">สรุปยอดขาย</div>
                </div>
            </div>

            <div class="option-frame" id="menumodify">
                <div class="option">
                    <div class="option-name" id="payment">แก้ไขเมนู</div>
                </div>
            </div>

        </div>
    </top>


    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <a href="dashboard.php?p=daily" class="btn btn-info">ยอดขายรายวัน</a> 
            <a href="dashboard.php?p=monthy" class="btn btn-success">ยอดขายรายเดือน</a> 
            <a href="dashboard.php?p=yearly" class="btn btn-warning">ยอดขายรายปี</a> 
          </div>
      </div>
    </div>


    <?php

    $p = (isset($_GET['p']) ? $_GET['p'] : '');
    
    if($p=='daily'){
      include('daily.php');
    }elseif($p=='monthy'){
      include('monthy.php');
    }elseif($p=='yearly'){
      include('yearly.php');
    }else{
      include('daily.php');
    }



    $con = mysqli_connect("localhost", "root", "", "rodrudee");
   
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $query = "SELECT menu.name, COUNT(orders.menu_id) AS total_orders
              FROM menu
              JOIN orders ON menu.menu_id = orders.menu_id
              GROUP BY menu.menu_id
              ORDER BY total_orders ASC
              LIMIT 5";
    $result = mysqli_query($con, $query);

    echo '<p class="best-seller"> รายการขายดีที่สุด </p>';

    echo '<div class="box-test1-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="box-test1-container">';
        echo '<div class="box-test1">';
        echo '<p class="p-name"><h3>' . $row["name"] . '</h3></p>';
        echo '<p class="p-name">' . $row["total_orders"] . ' คำสั่ง' .'</p>';
        echo '</div>';
    }
    echo '</div>';

    mysqli_close($con);
    ?>



    <script>
        document.getElementById("menumodify").addEventListener("click", function() {
            window.location.href = "../Menu/view_menu/menu.php";
        });
    </script>

  


  </body>


  
</html>


