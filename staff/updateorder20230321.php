<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if ($_SESSION['user_role'] != "waiters") {

    echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");

    exit();
} ?> <?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white">

    <h1 class="text-center"><strong>Update Order</strong></h1>

    <hr>

    <p class="text-center">Order Display</p>

    <table id="tblCurrentOrder" class="table bg-light" width="100%" cellspacing="0">
        <thead class="bg-light text-dark">
            <tr>
                <td colspan="5" class="bg-secondary"><input type="text" required="required" onchange="refreshTableOrder()" class="form-control" id="orderid" name="orderid" placeholder="Nomer Order"></td>
            </tr>

            <th>Menu</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
        </thead>

        <tbody id="tblorderlist"></tbody>

    </table>
    <input class="btn btn-dark btn-lg col-12" type="submit" name="update" onclick="cetakulang()" value="UPDATE">

</div>

<script src="vendor/jquery/jquery.min.js"></script>

<!-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<script type="text/javascript">
    function updateqty(orderid, itemid, qty) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "updateordersave.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                refreshTableOrder()
            }
        };
        xhr.send("id=" + orderid + "&itemid=" + itemid + "&qty=" + qty + "&action=update");
    }

    function deleteRow(orderid, itemid) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "updateordersave.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                refreshTableOrder()
            }
        };
        xhr.send("id=" + orderid + "&itemid=" + itemid + "&action=delete");
    }

    function cetakulang() {
        orderid = document.getElementById('orderid').value;
        total = document.getElementById('total').value;
        bayar = document.getElementById('bayar').value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "updateordersave.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                refreshTableOrder()
                window.open("struk.php?orderid=" + document.getElementById('orderid').value);
            }
        };
        xhr.send("id=" + orderid + "&total=" + total + "&bayar=" + bayar + "&action=cetak");

        // window.open("struk.php?orderid=" + document.getElementById('orderid').value);
    }

    function refreshTableOrder() {
        $("#tblorderlist").load("updateorderlist.php?orderid=" + document.getElementById('orderid').value);
    }

    function editStatus(objBtn, orderID) {
        var status = objBtn.value;
        $.ajax({
            url: "editstatus.php",
            type: 'POST',
            data: {

                orderID: orderID,

                status: status

            },

            success: function(output) {

                refreshTableOrder();

            }

        });

    }
</script> <?php include 'footer.php'; ?>