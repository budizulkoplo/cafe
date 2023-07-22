<?php include("../functions.php");
if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");
if ($_SESSION['user_level'] != "staff") header("Location: login.php");
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
    <h1><strong>INVENTORY LIST</strong></h1>
    <hr>
    <p>Daftar bahan baku.</p>
    <div class="col-12 p-3 p-md-5">
        <form action="addinventory.php" method="POST" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <?php if (in_array($_SESSION['user_category'], array("admin", "super admin"))) { ?>

                    <select name="storeid" class="form-control"> <?php $catpengeluaran = "SELECT storeid, `desc` FROM tbl_store";
                                                                    if ($res = $sqlconnection->query($catpengeluaran)) {
                                                                        while ($id = $res->fetch_array(MYSQLI_ASSOC)) {
                                                                            echo "<option value='" . $id['storeid'] . "'>" . ucfirst($id['desc']) . "</option>";
                                                                        }
                                                                    } ?> </select>
                <?php  } else { ?>
                    <input type="hidden" required="required" name="storeid" class="form-control" value="<?php echo $_SESSION['storeid']; ?>" aria-label="Add" aria-describedby="basic-addon2">
                <?php } ?>
                <select name="kategori" class="form-control"> <?php $catpengeluaran = "SELECT namakategori FROM tbl_catpengeluaran";

                                                                if ($res = $sqlconnection->query($catpengeluaran)) {

                                                                    if ($res->num_rows == 0) {

                                                                        echo "no category";
                                                                    }

                                                                    while ($category = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                        echo "<option value='" . $category['namakategori'] . "'>" . ucfirst($category['namakategori']) . "</option>";
                                                                    }
                                                                } ?> </select><input type="text" required="required" name="namaitem" class="form-control" placeholder="Nama Item" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="qty" class="form-control" placeholder="Stok Awal" aria-label="Add" aria-describedby="basic-addon2">
                <select name="satuan" class="form-control">
                    <?php $catpengeluaran = "SELECT nama FROM tbl_satuan";
                    if ($res = $sqlconnection->query($catpengeluaran)) {
                        while ($id = $res->fetch_array(MYSQLI_ASSOC)) {
                            echo "<option value='" . $id['nama'] . "'>" . ucfirst($id['nama']) . "</option>";
                        }
                    } ?>
                </select>
                <div class="input-group-append"> <button type="submit" name="addinventory" class="btn btn-primary"> (+) </button></div>
            </div>
        </form>
    </div>
    <button class="btn btn-primary" onclick="window.location.href='cetakinventory.php', target='_blank'">Cetak Inventory</button>
    <button class="btn btn-danger" onclick="simpanopname()">Update Opname</button>
    <div style="overflow-x:auto;">
        <table class="table table-responsive table-lg shadow" id="item" width="100%" cellspacing="0">
            <thead>
                <tr class="bg-dark text-white center">
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Nama Item</th>
                    <th>Qty</th>
                    <th>Opname</th>
                    <th>Satuan</th>
                    <th>Price/Unit</th>
                    <th>Store</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (in_array($_SESSION['user_category'], array("admin", "super admin"))) {
                    $displaystoreQuery = "select idinventory, a.kategori, nama, jml, opname, unit, idstore, `desc` as storename,unitprice from tbl_inventory a join tbl_store b on a.idstore=b.storeid and a.active='1'";
                } else {
                    $storeid = $_SESSION['storeid'];
                    $displaystoreQuery = "select idinventory, a.kategori, nama, jml,opname, unit, idstore, `desc` as storename,unitprice from tbl_inventory a join tbl_store b on a.idstore=b.storeid where a.active='1' and a.idstore='{$storeid}'";
                    // echo $displaystoreQuery;
                }
                if ($result = $sqlconnection->query($displaystoreQuery)) {
                    if ($result->num_rows == 0) {
                        echo "<td colspan='4'>Store belum tersedia.</td>";
                    }
                    $storeno = 1;
                    while ($store = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr>
                            <td><?php echo $storeno++; ?></td>
                            <!-- disini action rubah kategori nya -->
                            <td>

                                <form method="POST" action="addinventory.php?action=updatekategorivalue&id=<?php echo $store['idinventory']; ?>"> <input type="hidden" name="kategori" value="<?php echo $store['kategori']; ?>" /> <select name="role" class="form-control" onchange="this.form.submit()"> <?php $roleQuery = "SELECT namakategori FROM tbl_catpengeluaran";

                                                                                                                                                                                                                                                                                                            if ($res = $sqlconnection->query($roleQuery)) {

                                                                                                                                                                                                                                                                                                                if ($res->num_rows == 0) {

                                                                                                                                                                                                                                                                                                                    echo "no kategori";
                                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                                while ($role = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                                                                                                                                                                                                                                                                    if ($role['namakategori'] == $store['kategori']) echo "<option selected='selected' value='" . $store['kategori'] . "'>" . ucfirst($store['kategori']) . "</option>";

                                                                                                                                                                                                                                                                                                                    else echo "<option value='" . $role['namakategori'] . "'>" . ucfirst($role['namakategori']) . "</option>";
                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                            } ?> </select> <noscript><input type="submit" value="Submit"></noscript></form>

                            </td>
                            <!-- sampai sini -->
                            <td><?php echo $store['nama']; ?></td>
                            <td><?php echo $store['jml']; ?></td>
                            <td><input type="text" id="opname" name="opname" value="<?php echo $store['opname']; ?>" style="width: 80px;" onchange="updateopname(<?php echo $store['idinventory']; ?>, this.value)"></td>
                            <td><?php echo $store['unit']; ?></td>
                            <td>Rp. <?php echo  number_format($store['unitprice'], 0, '', '.'); ?></td>
                            <td><?php echo $store['storename']; ?></td>
                            <td nowrap>
                                <a title="delete menu" class="text-danger" href="addinventory.php?action=delete&id=<?php echo $store["idinventory"] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">

                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />

                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />

                                    </svg></a> | <a class="btn btn-success" href="belanja.php?id=<?php echo $store["idinventory"] ?>&kategori=<?php echo ucfirst($store['kategori']) ?>&nama=<?php echo ucfirst($store['nama']) ?>&satuan=<?php echo ucfirst($store['unit']) ?>">BELANJA</a>

                            </td>
                        </tr> <?php }
                        } else {
                            echo $sqlconnection->error;
                            echo "Something wrong.";
                        } ?>
            </tbody>
        </table>
    </div>
</div>
</div> <?php include 'footer.php'; ?>

<script>
    function updateJml(id, qty) {
        var jml = document.getElementById("qty").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "addinventory.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Data has been updated");
                location.reload();
            }
        };
        xhr.send("id=" + id + "&jml=" + qty + "&action=update");
    }

    function addJml(id, qty) {
        var jml = document.getElementById("qty").value;
        var add = document.getElementById("add").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "addinventory.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Data has been updated");
                location.reload();
            }
        };
        xhr.send("id=" + id + "&jml=" + qty + "&action=add");
    }

    function updateopname(id, qty) {
        var jml = document.getElementById("opname").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "addinventory.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Data has been updated");
                location.reload();
            }
        };
        xhr.send("id=" + id + "&jml=" + qty + "&action=opname");
    }

</script>

<script>
    $(document).ready(function() {
        $('#item').DataTable({
            'iDisplayLength': 100
        });
    });
</script>