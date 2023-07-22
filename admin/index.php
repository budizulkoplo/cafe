<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))  header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: ../index.php"); ?> <?php include 'header.php'; ?><div class="col-12 col-md-3"> </div>

<div class="col-12 col-md-6 p-3 p-md-5 text-center">



    <div>



        <div>

            <p> Selamat datang di POS Cafe system</p>

            <p> PT Mentari Multi Trada Group.</p>

            <p> Food & Resto.</p>

        </div>

    </div>

</div>



</div><?php include 'footer.php'; ?>