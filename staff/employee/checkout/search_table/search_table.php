<?php
session_start();

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../../../../rodrudee.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
}

$tablesQuery = "SELECT * FROM tables";
$tablesResult = $db->query($tablesQuery);

if ($tablesResult) {
    $tables = array();
    while ($row = $tablesResult->fetchArray(SQLITE3_ASSOC)) {
        $tables[] = $row;
    }
} else {
    $tables = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['table']) && !empty($_POST['table'])) {
        $_SESSION['table_id'] = $_POST['table'];
        $selectedTableId = $_SESSION['table_id'];

        $selectOrdersQuery = "SELECT * FROM orders WHERE table_id = '$selectedTableId';";
        $ordersResult = $db->query($selectOrdersQuery);

        if ($ordersResult) {
            $firstOrderData = $ordersResult->fetchArray(SQLITE3_ASSOC);
            if ($firstOrderData) {
                $firstOrderId = $firstOrderData['order_id'];
                $transactionId = generateUniqueTransactionId();

                do {
                    $insertTransactionQuery = "INSERT INTO transactions (transaction_id, order_id, transaction_time) 
                                VALUES ('$transactionId', '{$firstOrderData['order_id']}', CURRENT_TIMESTAMP)";

                    $result = $db->exec($insertTransactionQuery);

                    if ($result !== TRUE) {
                        echo "Error inserting transaction. Please try again later.";
                        error_log("Error inserting transaction: " . $db->lastErrorMsg());
                    }
                } while ($firstOrderData = $ordersResult->fetchArray(SQLITE3_ASSOC));

                header("Location: ../method/method.php");
                exit();
            } else {
                echo '<script>alert("No open orders for the selected table.");</script>';
            }
        } else {
            echo '<script>alert("Please select a table.");</script>';
        }
    }
}

$db->close();

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

    <content>
        <form method="post" action="">
            <div class="search-table">
                <div class="option-name" id="tableLabel"><span>โต๊ะ</span></div>
                <div class="text-field">
                    <select id="tableInput" name="table" class="select-table">
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