<?php
include("functions.php");

//checking username and password input
if (isset($_POST['username']) && isset($_POST['password'])) {

    //prevent sql injection by escaping special characters
    $username = $sqlconnection->real_escape_string($_POST['username']);
    $password = $sqlconnection->real_escape_string($_POST['password']);

    //sql statement
    $sql1 = "update tbl_staff set status='Online' WHERE username='$username' AND password = '$password'";
    $sqlconnection->query($sql1);

    //update daftar transaksi yang tidak di exsekusi
    $batalkonsumsi = "update tbl_consumption set status='expired' WHERE inputdate < curdate() and status='waiting'";
    $sqlconnection->query($batalkonsumsi);
    $batalorder = "update tbl_order set status='expired' WHERE order_date < curdate() and status='waiting'";
    $sqlconnection->query($batalorder);
    $complete = "update tbl_order set status='Completed' WHERE order_date < curdate() and status='ready'";
    $sqlconnection->query($complete);

    $sql = "SELECT * FROM vwuser WHERE username ='$username' AND password = '$password'";

    if ($result = $sqlconnection->query($sql)) {

        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $uid = $row['id'];
            $username = $row['username'];
            $role = $row['role'];
            $level = $row['level'];
            $storeid = $row['storeid'];
            $storename = $row['storename'];
            $confignamaresto = $row['confignamaresto'];
            $configalamat = $row['configalamat'];
            $confignotelp = $row['confignotelp'];
            $catatan = $row['catatan'];
            $img = $row['img'];


            $_SESSION['uid'] = $uid;
            $_SESSION['username'] = $username;
            $_SESSION['user_level'] = $level;
            $_SESSION['user_role'] = $role;
            $_SESSION['storeid'] = $storeid;
            $_SESSION['storename'] = $storename;
            $_SESSION['confignamaresto'] = $confignamaresto;
            $_SESSION['configalamat'] = $configalamat;
            $_SESSION['confignotelp'] = $confignotelp;
            $_SESSION['catatan'] = $catatan;

            if ($storeid == 0 || $role == 'super admin') {
                $_SESSION['img'] = "logopt.png";
            } else {
                $_SESSION['img'] = $img;
            }

            echo "correct";
        } else {
            echo "Wrong username or password.";
        }
    }
}
