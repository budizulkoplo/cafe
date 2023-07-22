<?php
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

?>
<!DOCTYPE html>

<html lang="en">



<head>
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
        }
    </style>

    <meta name="mobile-web-app-capable" content="yes">
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link href="staff.css" rel="stylesheet">
    <link href="../include/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="../include/css/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <!-- select2 -->
    <link href="css/select2.css" rel="stylesheet" />
    <!-- select2 -->

    <title>Staff Resto</title>

</head>

<nav class="navbar navbar-expand-lg navbar-light bg-dark text-white sticky-top">

    <div class="container-fluid"> <a class="navbar-brand"><img width="80" class="img=fluid" src="<?php echo $baseUrl . "/image/" . $_SESSION['img']; ?>" /></a> <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation"> <i class="fas fa-ellipsis-v"></i> </button>

        <div class="collapse navbar-collapse text-center justify-content-end" id="navbarNavAltMarkup">

            <div class="navbar-nav"> <a class="nav-link text-white" href="index.php"> <i class="fas fa-concierge-bell"></i> <span>Home</span> </a> <?php if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="order.php"> <i class="fas fa-utensils"></i> <span>Order</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    // if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                    //     echo ' <a class="nav-link text-white" href="order20230401.php"> <i class="fas fa-utensils"></i> <span>Order Old</span></a> ';
                                                                                                                                                    // }

                                                                                                                                                    if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="kitchen.php"> <i class="fas fa-tv"></i> <span>Status</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="inventory.php"> <i class="fas fa-warehouse"></i> <span>Belanja</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    if ($_SESSION['user_role'] == "kitchen") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="inventory.php"> <i class="fas fa-warehouse"></i> <span>Inventory</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    if ($_SESSION['user_role'] == "kitchen") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="menu.php"> <i class="fas fa-utensils"></i> <span>Menu</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    if ($_SESSION['user_role'] == "kitchen") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="resep.php"> <i class="fas fa-book"></i> <span>Resep</span></a> ';
                                                                                                                                                    }

                                                                                                                                                    if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="updateorder.php"> <i class="fas fa-calculator"></i> <span>Update Order</span></a> ';
                                                                                                                                                    }
                                                                                                                                                    if ($_SESSION['user_role'] == "waiters") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="operasional.php"> <i class="fas fa-calculator"></i> <span>Operasional</span></a> ';
                                                                                                                                                    }

                                                                                                                                                    if ($_SESSION['user_role'] == "kitchen") {

                                                                                                                                                        echo ' <a class="nav-link text-white" href="kitchen.php"> <i class="fas fa-tv"></i> <span>Status</span></a> ';
                                                                                                                                                    } ?>
                <div class="dropdown" align="center">
                    <button style="border: none; background-color: transparent;" class="nav-link text-white dropdown-toggle" style="background-color: #343A40;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chart-line"></i> Report
                    </button>
                    <div align="center" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="konsumsi.php">Laporan Konsumsi</a>
                        <!-- <a class="dropdown-item" href="laporanmenu.php">Sell Menu</a> -->
                        <a class="dropdown-item" href="rekapan.php">Rekapan Harian</a>
                        <!-- <a class="dropdown-item" href="harian.php">Laporan Transaksi</a> -->
                        <a class="dropdown-item" href="pengeluaran.php">Pengeluaran</a>
                        <a class="dropdown-item" href="general.php">General</a>
                    </div>
                </div>
                <a class="nav-link text-white" href="#" data-toggle="modal" data-target="#logoutModal"> <i class="fas fa-power-off"></i> <span>Off</span> </a>

            </div>

        </div>

    </div>

</nav>

<div class="container">

    <div class="row">