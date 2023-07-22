<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if (!in_array($_SESSION['user_role'], array("kitchen", "waiters"))) {

    echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");

    exit();
} ?> <?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white">

    <h3 class="text-center"><strong>Order Display</strong></h3>
    <hr>
    <table id="tblCurrentOrder" class="table shadow text-center bg-light text-dark" width="100%" cellspacing="0">

        <thead class="bg-dark text-white">

            <th>#</th>

            <th>Group</th>

            <th>Menu's</th>

            <th>Qty</th>

            <th>Status</th>

            <th></th>

        </thead>

        <tbody id="tblBodyCurrentOrder"></tbody>

    </table>

</div>

<script src="vendor/jquery/jquery.min.js"></script>

<!-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        refreshTableOrder();

    });



    function refreshTableOrder() {

        $("#tblBodyCurrentOrder").load("displayorder.php?cmd=currentorder");

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

    setInterval(function() {

        refreshTableOrder();

    }, 3000);
</script> <?php include 'footer.php'; ?>