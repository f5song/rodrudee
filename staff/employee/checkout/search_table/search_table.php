<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rodrudee";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tablesQuery = "SELECT * FROM tables";
$tablesResult = $conn->query($tablesQuery);

if ($tablesResult) {
    $tables = $tablesResult->fetch_all(MYSQLI_ASSOC);
} else {
    $tables = array(); // Default to an empty array if there's an error or no data
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['table']) && !empty($_POST['table'])) {
        $_SESSION['table_id'] = $_POST['table'];
        $selectedTableId = $_SESSION['table_id']; // Assign selected table to $selectedTableId

        // Check if there are open orders for the selected table
        $selectOrdersQuery = "SELECT * FROM orders WHERE table_id = '$selectedTableId' AND pay_status = 'ยังไม่จ่าย'";
        $ordersResult = $conn->query($selectOrdersQuery);

        if ($ordersResult && $ordersResult->num_rows > 0) {
            // Fetch the first order data to get the same transaction_id for all orders
            $firstOrderData = $ordersResult->fetch_assoc();
            $firstOrderId = $firstOrderData['order_id'];
            $transactionId = generateUniqueTransactionId();

            do {
                // Insert data into the transactions table with the same transaction_id for all orders
                $insertTransactionQuery = "INSERT INTO transactions (transaction_id, order_id, transaction_time) 
                                            VALUES ('$transactionId', '{$firstOrderData['order_id']}', NOW())";

                if ($conn->query($insertTransactionQuery) !== TRUE) {
                    echo "Error: " . $insertTransactionQuery . "<br>" . $conn->error;
                }
            } while ($firstOrderData = $ordersResult->fetch_assoc());

            // Redirect after successfully inserting data
            header("Location: ../method/method.php");
            exit();
        } else {
            echo '<script>alert("No open orders for the selected table.");</script>';
        }
    } else {
        echo '<script>alert("Please select a table.");</script>';
    }
}

$conn->close();

// Function to generate a unique transaction_id
function generateUniqueTransactionId()
{
    return uniqid('TRANS_', true);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Search Table</title>
</head>

<body>
    <header>
        <img src="../../../asset/profile.png" alt="profile">
        <span>พนักงาน</span>
    </header>
    <top>
        <div>
            <div class="queue">
                <!-- <div class="queue_frame">
                    <img src="../../../asset/queuewithbg.png">
                    <div class="num_queue">3 คิว</div>
                </div> -->
            </div>
            <div class="yellow-bar"></div>
        </div>
        <div class="option_container">
            <div class="option-frame">
                <div class="option">
                    <img src="../../../asset/cooking.png">
                    <div class="option-name" id="status">เช็คสถานะอาหาร</div>
                </div>
            </div>
            <div class="option-frame">
                <div class="option">
                    <img src="../../../asset/bill.png">
                    <div class="option-name" id="payment">หน้าชำระเงิน</div>
                </div>
            </div>
        </div>
    </top>

    <content>
        <form method="post" action="">
            <div class="search-table">
                <div class="option-name" id="tableLabel">โต๊ะ</div>
                <div class="text-field">
                    <select id="tableInput" name="table">
                        <?php
                        foreach ($tables as $table) {
                            echo "<option value='{$table['table_id']}'>{$table['table_id']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="button-container">
                <button class="button" type="submit">สรุปยอดชำระ</button>
            </div>
        </form>
    </content>
</body>

</html>
