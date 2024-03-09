<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedMenuIds = json_decode($_POST['selectedMenuIds'], true);
    $_SESSION['selectedMenuIds'] = $selectedMenuIds;

    session_write_close();
    http_response_code(200);
    echo 'Session updated successfully';
} else {
    http_response_code(404);
    echo 'Not Found';
}
?>
