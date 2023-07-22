<?php
include("../functions.php");

//checking username and password input
if (isset($_POST['username']) && isset($_POST['password'])) {

    //prevent sql injection by escaping special characters
    $username = $sqlconnection->real_escape_string($_POST['username']);
    $password = $sqlconnection->real_escape_string($_POST['password']);

    //sql statement
    $sql1 = "update tbl_staff set status='Online' WHERE username='$username' AND password = '$password'";
    $sqlconnection->query($sql1);

    $sql = "SELECT * FROM tbl_staff a, tbl_store b WHERE a.storeid=b.storeid and username='$username' AND password = '$password'";

    if ($result = $sqlconnection->query($sql)) {

        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $uid = $row['staffID'];
            $username = $row['username'];
            $role = $row['role'];
            $storeid = $row['storeid'];
            $storename = $row['storename'];

            $_SESSION['uid'] = $uid;
            $_SESSION['username'] = $username;
            $_SESSION['user_level'] = "staff"; // 1 - admin 2 - staff
            $_SESSION['user_role'] = $role;
            $_SESSION['storeid'] = $storeid;
            $_SESSION['storename'] = $storename;

            echo "correct";
        } else {
            echo "Wrong username or password.";
        }
    }
}
