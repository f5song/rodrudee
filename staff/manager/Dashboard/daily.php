<?php
// Connect to SQLite database
$db = new SQLite3('../../../rodrudee.db');
if (!$db) {
    die("Connection failed: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">

    <title>ผู้จัดการ</title>
</head>

<body style="background-color: #FFF8EE;">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                $query = "
                    SELECT strftime('%d-%m-%Y', transaction_time) AS datesave, SUM(total) AS total_sum
                    FROM transactions
                    GROUP BY strftime('%d-%m-%Y', transaction_time)
                    ORDER BY transaction_time DESC
                ";
                $result = $db->query($query);
                $resultchart = $db->query($query);

                $datesave = array();
                $total_sum = array();

                while ($rs = $resultchart->fetchArray(SQLITE3_ASSOC)) {
                    $datesave[] = "\"" . $rs['datesave'] . "\"";
                    $total_sum[] = "\"" . $rs['total_sum'] . "\"";
                }

                $datesave = implode(",", $datesave);
                $total_sum = implode(",", $total_sum);
                ?>

                <script type="text/javascript"
                    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
                <hr>

                <p align="center">
                    <canvas id="myChart" width="800px" height="300px"></canvas>
                    <script>
                        var ctx = document.getElementById("myChart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [<?php echo $datesave; ?>],
                                datasets: [{
                                    label: 'รายงานรายได้ แยกตามวัน (บาท)',
                                    data: [<?php echo $total_sum; ?>],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </p>

                <div class="col-sm-12">
                    <h3>ข้อมูลยอดขาย</h3>
                    <table class="table table-striped" border="1" cellpadding="0" cellspacing="0" align="center">
                        <thead>
                            <tr class="table-primary">
                                <th width="20%">ว/ด/ป</th>
                                <th width="50%">รหัสการสั่งซื้อ</th>
                                <th width="20%">วิธีการจ่ายเงิน</th>
                                <th width="20%"><center>รายได้</center></th>
                            </tr>
                        </thead>

                        <?php
                        $sql = "
                            SELECT * FROM transactions
                            ORDER BY transaction_id DESC
                        ";
                        $result2 = $db->query($sql);
                        $amount_total = 0;

                        while ($row2 = $result2->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                            <tr>
                                <td><?php echo $row2['transaction_time']; ?></td>
                                <td><?php echo $row2['transaction_id']; ?></td>
                                <td><?php echo $row2['payment_method']; ?></td>
                                <td align="right"><?php echo number_format($row2['total'], 2); ?></td>
                            </tr>
                        <?php
                            $amount_total += $row2['total'];
                        }
                        ?>

                        <tr class="table-danger">
                            <td align="center"></td>
                            <td align="center">รวม</td>
                            <td align="center"></td>
                            <td align="right"><b>
                                    <?php echo number_format($amount_total, 2); ?></b></td>
                        </tr>
                    </table>
                </div>
                <?php
                $db->close();
                ?>
            </div>
        </div>
    </div>
</body>

</html>
