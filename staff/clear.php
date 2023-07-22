
    <?php
    session_start();
    require("../conf/dbconnection.php");

    $order = "delete from tbl_order
    where orderID not in(select orderid from tbl_orderdetail)";

    $sqlconnection->query($order);

    header("Location: login.php");
    ?>