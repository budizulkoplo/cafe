<?php
include("../functions.php");
$username = $_SESSION['username'];
if ((isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['user_level']))) {
    if ($_SESSION['user_level'] == "staff") {
        $sql1 = "update tbl_staff set status='Offline' WHERE username='$username' ";
        $sqlconnection->query($sql1);
        session_destroy();
        header("Location: ../index.php");
    } else
        header("Location: ../index.php");
} else
    header("Location: ../index.php");
