<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            // Connect to SQLite database
            $db = new SQLite3('../../../rodrudee.db');
            if (!$db) {
                die("Connection failed: " . $db->lastErrorMsg());
            }

            $query = "
                SELECT order_id,total, SUM(total) AS total_sum, strftime('%Y', transaction_time) AS datesave
                FROM transactions
                GROUP BY strftime('%Y', transaction_time)
                ORDER BY transaction_time DESC
            ";

            $result = $db->query($query);
            $resultchart = $db->query($query);

            //for chart
            $datesave = array();
            $total_sum = array();

            while ($rs = $resultchart->fetchArray(SQLITE3_ASSOC)) {
                $datesave[] = "\"" . $rs['datesave'] . "\"";
                $total_sum[] = "\"" . $rs['total_sum'] . "\"";
            }

            $datesave = implode(",", $datesave);
            $total_sum = implode(",", $total_sum);
            ?>

            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
            <hr>
            <p align="center">
                <canvas id="myChart" width="800px" height="300px"></canvas>
                <script>
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: [<?php echo $datesave;?>
                
                ],
                datasets: [{
                label: 'รายงานรายได้ แยกตามปี (บาท)',
                data: [<?php echo $total_sum;?>
                ],
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
                beginAtZero:true
                }
                }]
                }
                }
                });
                </script>
            </p>
            <div class="col-sm-4">
                <h3>ข้อมูลยอดขาย</h3>
                <table  class="table table-striped" border="1" cellpadding="0"  cellspacing="0" align="center">
                    <thead>
                        <tr class="table-primary">
                            <th width="30%">ว/ด/ป</th>
                            <th width="70%"><center>รายได้</center></th>
                        </tr>
                    </thead>
                    
                    <?php
                    $amount_total = 0;
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    ?>
                        <tr>
                            <td><?php echo $row['datesave'];?></td>
                            <td align="right"><?php echo number_format($row['total_sum'], 2);?></td>
                        </tr>
                    <?php
                        $amount_total += $row['total_sum'];
                    }
                    ?>
                    <tr class="table-danger">
                        <td align="center">รวม</td>
                        <td align="right"><b>
                        <?php echo number_format($amount_total, 2);?></b></td></td>
                    </tr>
                </table>
            </div>
            <?php
            $db->close();
            ?>
        </div>
    </div>
</div>
