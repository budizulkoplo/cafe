<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

function getStatus()

{

    global $sqlconnection;

    $checkOnlineQuery = "SELECT status FROM tbl_staff WHERE staffID = {$_SESSION['uid']}";

    if ($result = $sqlconnection->query($checkOnlineQuery)) {

        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            return $row['status'];
        }
    } else {

        echo "Something wrong the query!";

        echo $sqlconnection->error;
    }
} ?> <?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3"></div>

<div class="col-12 col-md-6 p-3 p-md-5 background-cerah text-center"> <img width="100" class="img-fluid" src="../image/staff.png" /><br /> Hai : <?php echo $_SESSION['username'] ?> , status <?php if (getStatus() == 'Online') echo "<input type='submit' class='btn btn-success' name='btnStatus' value='Online'>";

                                                                                                                                                                                                else echo "<input type='submit' class='btn btn-danger myBtn' name='btnStatus' value='Offline'>" ?> <br /></div>

<div class="col-12 col-md-6 p-3 p-md-5 bg-dark text-white">

    <div class="row">

        <div class="col-md-12 col-12 text-white">

            <div class="card-header text-center text-white"> Alert Order</div>

            <div class="card-body">

                <table id="orderTable" class="table text-white" width="100%" cellspacing="0"></table>

            </div>

            <div class="card-footer small text-muted"><i>Automatic Refresh order in 3 second</i></div>

        </div>

    </div>

</div>

<script src="vendor/jquery/jquery.min.js"></script>

<!-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<script src="js/sb-admin.min.js"></script>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        refreshTableOrder();

    });



    function refreshTableOrder() {

        $("#orderTable").load("displayorder.php?cmd=currentready");

    }

    setInterval(function() {

        refreshTableOrder();

    }, 3000);
</script> <?php include 'footer.php'; ?>