<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if ($_SESSION['user_role'] != "waiters") {

    echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");

    exit();
}
if (!isset($_GET['tanggal'])) {
    $tanggal = date('Y-m-d');
} else {
    $tanggal = $_GET['tanggal'];
}
?> <?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white text-center">

    <h1 class="text-center"><strong>Opersional</strong></h1>

    <hr>

    <p>Operasional list.</p>

    <div class="col-12 p-3 p-md-5">

        <form action="addtransaksi.php" method="POST" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">


                <input type="text" class="datepicker form-control" name="tgltransaksi" id="tgllaporan" placeholder="Tgl Transaksi" value="<?php echo  $tanggal ?>" autocomplete="off">
                <select name="kategori" id="kategori" class="form-control"> <?php $catpengeluaran = "SELECT namakategori FROM tbl_catpengeluaran";

                                                                            if ($res = $sqlconnection->query($catpengeluaran)) {

                                                                                if ($res->num_rows == 0) {

                                                                                    echo "no category";
                                                                                }

                                                                                while ($category = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                                    echo "<option value='" . $category['namakategori'] . "'>" . ucfirst($category['namakategori']) . "</option>";
                                                                                }
                                                                            } ?> </select>
                <select name="namatransaksi" id="item1" class=" form-control select2"> <?php $item = "SELECT * FROM tbl_inventory";

                                                                                        if ($res = $sqlconnection->query($item)) {

                                                                                            if ($res->num_rows == 0) {

                                                                                                echo "no category";
                                                                                            }

                                                                                            while ($item = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                                                echo "<option value='" . $item['nama'] . "'>" . ucfirst($item['nama']) . "</option>";
                                                                                            }
                                                                                        } ?> </select>
                <input type="text" required="required" name="namatransaksi" id="item2" class="form-control" placeholder="Nama Transaksi" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" required="required" name="jumlah" class="form-control" placeholder="Jumlah" aria-label="Add" aria-describedby="basic-addon2">
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" aria-label="Add" aria-describedby="basic-addon2">
                <div class="input-group-append"> <button type="submit" name="addrs" class="btn btn-primary"> (+) </button></div>
            </div>

        </form>

    </div>
    <?php
    $storeid = $_SESSION['storeid'];
    $Cash = "select * from cashonhand where idstore='{$storeid}'";
    // echo $displaystoreQuery;
    if ($result = $sqlconnection->query($Cash)) {
        if ($result->num_rows == 0) {
            echo "<td colspan='4'>data belum tersedia.</td>";
        }
        while ($rs = $result->fetch_array(MYSQLI_ASSOC)) { ?>
            <div><label>Cash on Hand</label> <input type="text" id="qty" name="jml" value="<?php echo $rs['nominal']; ?>" onchange="updatenominal(<?php echo $rs['idstore']; ?>)"></div>
    <?php
        }
    } ?>
    <div class="col-12 p-3 p-md-5">

        <table class="table table-responsive table-lg shadow text-center" id="dataTable" width="100%" cellspacing="0">

            <tr class="bg-dark text-white">
                <th>#</th>
                <th>Nama Transaksi</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th></th>

            </tr> <?php
                    if ($_SESSION['storeid'] == 0) {
                        $storeid = '%';
                    } else {
                        $storeid = $_SESSION['storeid'];
                    }
                    $sqloperasional = "SELECT * FROM tbl_operasional WHERE storeid like '{$storeid}' and tgltransaksi='{$tanggal}'";
                    if ($result = $sqlconnection->query($sqloperasional)) {
                        if ($result->num_rows == 0) {
                            echo "<td colspan='4'>Belum ada data transaksi.</td>";
                        }
                        $rsno = 1;
                        while ($rs = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr class="text-center">
                        <td><?php echo $rsno++; ?></td>
                        <td><?php echo $rs['namatransaksi']; ?></td>
                        <td><?php echo $rs['jumlah']; ?></td>
                        <td><?php echo $rs['keterangan']; ?></td>
                        </td>

                        <td class="text-center"><a href="deleters.php?rsID=<?php echo $rs['idoperasional']; ?>" class="btn btn-sm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />

                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />

                                </svg></a></td>

                    </tr> <?php }
                    } else {

                        echo $sqlconnection->error;

                        echo "Something wrong.";
                    } ?>

        </table>

    </div>

</div>

</div> <?php include 'footer.php'; ?>
<script src="../include/js/jquery-ui.min.js"></script>
<script src="../include/js/jquery-ui.js"></script>
<script>
    // JavaScript
    window.onload = function() {
        const kategori = document.getElementById("kategori");
        const text1 = document.getElementById("item1");
        const text2 = document.getElementById("item2");
        text1.style.display = "none";
        text2.style.display = "none";

        kategori.addEventListener("change", function() {
            if (kategori.value === "Operasional") {
                text1.style.display = "block";
                text2.style.display = "none";
            } else if (kategori.value === "Bahan Basah") {
                text1.style.display = "none";
                text2.style.display = "block";
            }
        });
    }


    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).ready(function() {
        // inisialisasi datepicker
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                // panggil fungsi untuk filter data
                filterData(dateText);
            }
        });





        // event onchange pada input tanggal
        $('#tgllaporan').on('change', function() {
            // ambil nilai tanggal yang baru
            var tanggal = $(this).val();
            // panggil fungsi untuk filter data
            filterData(tanggal);
        });
    });

    function filterData(tanggal) {
        // redirect halaman dengan menambahkan parameter tanggal ke URL
        window.location.href = "operasional.php?cmd=display&tanggal=" + tanggal;
    }

    function updatenominal(id) {
        var jml = document.getElementById("qty").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "cashupdate.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Data has been updated");
            }
        };
        xhr.send("id=" + id + "&jml=" + jml + "&action=update");
    }
</script>