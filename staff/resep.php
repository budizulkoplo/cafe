<?php include("../functions.php");
if ($_SESSION['user_level'] != "staff") header("Location: ../index.php");

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

    <h1><strong>DAFTAR RESEP</strong></h1>

    <hr>

    <p>restaurant resep list.</p>

    <div class="col-12 p-3 p-md-5">

        <table class="table table-responsive table-lg shadow text-center" id="dataTable" width="100%" cellspacing="0">

            <?php
            if ($_SESSION['storeid'] == 0) {
                $storeid = '%';
            } else {
                $storeid = $_SESSION['storeid'];
            }

            $namamenu = "";
            $resep = "SELECT itemid, menuitemname as namamenu, d.nama as komposisi, c.qty, d.unit as satuan, `desc` as namaresto, price,img
            FROM tbl_menuitem a join tbl_store b
            on a.storeid=b.storeid join tbl_detailinventory c 
            on a.itemID=c.idmenu
            join tbl_inventory d on c.idinventory=d.idinventory
            WHERE idstore like '{$storeid}'";

            if ($result = $sqlconnection->query($resep)) {

                if ($result->num_rows == 0) {

                    echo "<td colspan='4'>Resep belum tersedia.</td>";
                }


                while ($store = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr class="text-center">

                        <?php if ($namamenu <> $store['namamenu']) {
                            $storeno = 1; ?>
                    <tr class="bg-light text-black">
                        <td colspan="6"></td>
                    </tr>
                    <tr class="bg-dark text-white">
                        <td colspan="2"><?php echo $store['namamenu']; ?></td>
                        <td>Harga: Rp. <?php echo number_format($store['price'], 0, '', '.'); ?></td>
                        <td><img src="../image/<?= $store['img'] ?>" width="50px"></td>

                    </tr>
                    <tr class="bg-light text-black">
                        <th>#</th>
                        <th>Komposisi</th>
                        <th>Qty</th>
                        <th>Satuan</th>

                    </tr>

                <?php } ?>
                <td colspan=""><?php echo $storeno++; ?></td>
                <td><?php echo $store['komposisi']; ?></td>
                <td><?php echo $store['qty']; ?></td>
                <td><?php echo $store['satuan']; ?></td>


                </tr> <?php
                        $namamenu = $store['namamenu'];
                    }
                } else {

                    echo $sqlconnection->error;

                    echo "Something wrong.";
                } ?>

        </table>

    </div>

</div>

</div> <?php include 'footer.php'; ?>