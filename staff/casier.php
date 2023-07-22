<?php include("../functions.php");
if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");
if ($_SESSION['user_level'] != "staff") header("Location: login.php");
if ($_SESSION['user_role'] != "waiters") {
    echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");
    exit();
} ?> <?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white">
    <h1 class="text-center"><strong>Pembayaran</strong></h1>
    <hr>

    <div class="row">
        <div class="col-md-6">

            <table id="tblCurrentOrder" class="table shadow text-center bg-dark text-white" width="100%" cellspacing="0">
                <thead class="bg-warning text-dark">
                    <th>#</th>
                    <th>Group</th>
                    <th>Menu's</th>
                    <th>Qty</th>
                    <th>Status</th>
                </thead>
                <tbody id="tblBodyCurrentOrder"></tbody>
            </table>
        </div>

        <div class="col-md-6">
            <div class="card-header text-center bg-dark text-white">
                List Order</div>
            <div class="card-body">
                <form action="insertorder.php" method="POST">
                    <div id="tblOrderList">
                    </div>
                    <input class="btn btn-dark btn-lg col-12" type="submit" name="sentorder" value="BAYAR">
                </form>
            </div>
        </div>
    </div>

</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        refreshTableOrder();
    });

    function refreshTableOrder() {
        $("#tblBodyCurrentOrder").load("displaycasier.php?cmd=currentorder");
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

<script>
    var currentItemID = null;

    function displayItem(id) {
        $.ajax({
            url: "displayitem.php",
            type: 'POST',
            data: {
                btnMenuID: id
            },
            success: function(output) {
                $("#tblItem").html(output);
            }
        });
    }

    function insertItem(orderID) {
        var id = currentItemID;
        var quantity = $("#qty").val();
        $.ajax({
            url: "displaybayar.php",
            type: 'POST',
            data: {
                orderID: orderID,
                qty: quantity
            },
            success: function(output) {
                $("#tblOrderList").empty();
                $("#tblOrderList").append(output);
                $("#qtypanel").prop('hidden', true);
            }
        });
        $("#qty").val(1);
    }

    function setQty(id) {
        currentItemID = id;
        $("#qtypanel").prop('hidden', false);
    }
    $(document).on('click', '.deleteBtn', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        return false;
    });
</script>