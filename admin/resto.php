<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: login.php");

if (!empty($_POST['role'])) {

    $role = $sqlconnection->real_escape_string($_POST['role']);

    $storeID = $sqlconnection->real_escape_string($_POST['storeID']);

    $updateRoleQuery = "UPDATE tbl_store SET role = '{$role}'  WHERE storeID = {$storeID}  ";

    if ($sqlconnection->query($updateRoleQuery) === TRUE) {

        echo "";
    } else {

        echo "someting wong";

        echo $sqlconnection->error;
    }
} ?> <?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white text-center"> <i class="fas fa-user-circle fa-4x"></i>

    <h1><strong>RESTO CAFE LIST</strong></h1>

    <hr>

    <p>restaurant cafe store list.</p>

    <div class="col-12 p-3 p-md-5">

        <form action="addstore.php" method="POST" enctype="multipart/form-data" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

            <div class="input-group">

                <input type="text" required="required" name="storename" class="form-control" placeholder="Nama Resto" aria-label="Add" aria-describedby="basic-addon2">

                <input type="text" required="required" name="desc" class="form-control" placeholder="Deskripsi" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="confignama" class="form-control" placeholder="nama di nota" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="configalamat" class="form-control" placeholder="alamat" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="confignotelp" class="form-control" placeholder="no telp" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="catatan" class="form-control" placeholder="catatan" aria-label="Add" aria-describedby="basic-addon2">
                <input type="file" name="logo" placeholder="Logo Resto" aria-label="Add">
                <div class="input-group-append"> <button type="submit" name="addstore" class="btn btn-primary"> (+) </button></div>
            </div>

        </form>

    </div>

    <div style="overflow-x:auto;">

        <table class="table table-responsive table-lg shadow text-center" id="dataTable" width="100%" cellspacing="0">

            <tr class="bg-dark text-white">

                <th>#</th>
                <th>Nama Toko</th>
                <th>Desc</th>
                <th>Nama Toko di Nota</th>
                <th>Alamat</th>
                <th>Telp</th>
                <th>Catatan</th>
                <th>Logo</th>

            </tr> <?php $displaystoreQuery = "SELECT * FROM tbl_store";

                    if ($result = $sqlconnection->query($displaystoreQuery)) {

                        if ($result->num_rows == 0) {

                            echo "<td colspan='4'>Store belum tersedia.</td>";
                        }

                        $storeno = 1;

                        while ($store = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr class="text-center">

                        <td><?php echo $storeno++; ?></td>
                        <td><?php echo $store['desc']; ?></td>
                        <td><?php echo $store['storename']; ?></td>
                        <td><?php echo $store['confignamaresto']; ?></td>
                        <td><?php echo $store['configalamat']; ?></td>
                        <td><?php echo $store['confignotelp']; ?></td>
                        <td><?php echo $store['catatan']; ?></td>
                        <td><img src="../image/<?= $store['img'] ?>" width="50px"></td>
                    </tr> <?php }
                    } else {

                        echo $sqlconnection->error;

                        echo "Something wrong.";
                    } ?>

        </table>

    </div>

</div>

</div> <?php include 'footer.php'; ?>