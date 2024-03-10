<?php
session_start();
// session_unset();
// session_destroy();
// Unset the selectedMenuIds session variable
unset($_SESSION['selectedMenuIds']);
unset($_SESSION['totalPrice']);
unset($_SESSION['orderCount']);

?>
