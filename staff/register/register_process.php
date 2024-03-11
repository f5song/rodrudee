<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["password-confirm"];
    $role = $_POST["role"];

    if ($password != $passwordConfirm) {
        echo "Passwords do not match";
        exit();
    }

    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('../../rodrudee.db');
        }
    }

    $db = new MyDB();
    if (!$db) {
        echo $db->lastErrorMsg();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO staff (first_name, last_name, username, password, role) VALUES ('$firstName', '$lastName', '$username', '$hashedPassword', '$role')";

    if ($db->exec($sql) === TRUE) {
        header("Location: ../login/login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $db->lastErrorMsg();
    }

    $db->close();
}
?>
