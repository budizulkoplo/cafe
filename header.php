<!DOCTYPE html>

<html lang="en">
<?php
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

?>


<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link href="../include/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css" />
    <title>Admin Resto</title>

</head>

<nav class="navbar navbar-expand-lg navbar-light bg-dark text-white">

    <div class="container-fluid"> <a class="navbar-brand"><img height="50" class="img=fluid" src="<?php echo $baseUrl . "/image/" . $_SESSION['img']; ?>" /></a> <button class="navbar-toggler text-warning" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation"> <i class="fas fa-ellipsis-v"></i> </button>

        <div class="collapse navbar-collapse text-center justify-content-end" id="navbarNavAltMarkup">

            <div class="navbar-nav">

                <a class="nav-link text-warning" href="index.php"> <i class="fas fa-concierge-bell"></i> <span>Home</span> </a>
                <a class="nav-link text-warning" href="resto.php"><i class="fas fa-store"></i> <span>Resto</span> </a>
                <a class="nav-link text-warning" href="inventory.php"> <i class="fas fa-warehouse"></i> <span>Inventory</span></a>
                <a class="nav-link text-warning" href="menu.php"> <i class="fas fa-utensils"></i> <span>Menu</span></a>
                <a class="nav-link text-warning" href="resep.php"> <i class="fas fa-book"></i> <span>Resep</span></a>
                <a class="nav-link text-warning" href="staff.php"><i class="fas fa-user-circle"></i> <span>Staff</span> </a>
                <a class="nav-link text-warning" href="status.php"> <i class="fas fa-tv"></i> <span>Status</span> </a>
                <a class="nav-link text-warning" href="sales.php"> <i class="fas fa-chart-line"></i> <span>Report</span></a>
                <a class="nav-link text-warning" href="#" data-toggle="modal" data-target="#logoutModal"> <i class="fas fa-power-off"></i> <span>Off</span> </a>

            </div>

        </div>

    </div>

</nav>

<div class="container">

    <div class="row">