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
}
if (!isset($_GET['tanggal'])) {
    $tanggal = date('Y-m-d');
} else {
    $tanggal = $_GET['tanggal'];
}
$idbarang = $_GET['id'];
$kategori = $_GET['kategori'];
$namaitem = $_GET['nama'];
$satuan = $_GET['satuan'];

?><?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white text-center">

    <div class="col-12 p-3 p-md-5">

        <h3 align="center"><strong>Input Belanja</strong></h3>
        <form method="post" action="simpanbelanja.php">
            <div class="container">
                <div class="row">
                    <div align="center">
                        <div class="col-md-6">
                            <?php if (in_array($_SESSION['user_role'], array("admin", "super admin"))) { ?>
                                <div class="form-group">
                                    <label for="nama_menu">Resto:</label>
                                    <select name="storeid" id="storeid" class="form-control"> <?php $roleQuery = "SELECT * FROM tbl_store";
                                                                                                if ($res = $sqlconnection->query($roleQuery)) {
                                                                                                    if ($res->num_rows == 0) {
                                                                                                        echo "no role";
                                                                                                    }
                                                                                                    while ($role = $res->fetch_array(MYSQLI_ASSOC)) {
                                                                                                        echo "<option value='" . $role['storeid'] . "'>" . ucfirst($role['desc']) . "</option>";
                                                                                                    }
                                                                                                } ?> </select>

                                </div>
                            <?php  } else { ?>
                                <select name="storeid" id="storeid" class="form-control" style="display:none;">
                                    <option value="<?php echo $_SESSION['storeid']; ?>"><?php echo $_SESSION['storeid']; ?></option>
                                </select>
                            <?php } ?>
                            <div class="form-group">
                                <label for="nama_menu">Tanggal:</label>
                                <input type="text" class="datepicker form-control" name="tgltransaksi" id="tgllaporan" placeholder="Tgl Transaksi" value="<?php echo  $tanggal ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori:</label>
                                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $idbarang; ?>">
                                <input type="hidden" class="form-control" id="satuannya" name="satuannya" value="<?php echo $satuan; ?>">
                                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo $kategori; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Barang:</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $namaitem; ?>" readonly>

                            </div>
                            <div class="form-group">
                                <label for="totalharga">Total Belanja:</label>
                                <input type="text" class="form-control" id="totalharga" name="totalharga" required>
                            </div>
                            <div class="form-group">
                                <label for="jmlitem">Jumlah Item:</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="jmlitem" name="jmlitem" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"> <?php echo $satuan; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jmlitem">Harga Satuan:</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp. </div>
                                    </div>
                                    <input type="text" class="form-control" id="satuan" name="satuan" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ket">Keterangan:</label>
                                <input type="text" class="form-control" id="ket" name="ket">
                            </div>

                            <div class="form-group">
                                <br>
                                <button type="submit" class="form-control btn btn-success btn-block">SIMPAN</button>
                                <button class="form-control btn btn-warning btn-block" onclick="goBack()">Kembali</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

    </div>

</div>

</div> <?php include 'footer.php'; ?>
<script src="../include/js/jquery-ui.min.js"></script>
<script src="../include/js/jquery-ui.js"></script>
<script>
    function goBack() {
        window.history.back();
    }

    // Ambil inputan totalharga, jmlitem, dan satuan
    const totalhargaInput = document.getElementById('totalharga');
    const jmlitemInput = document.getElementById('jmlitem');
    const satuanInput = document.getElementById('satuan');

    // Tambahkan event listener untuk input jmlitem
    jmlitemInput.addEventListener('input', function() {
        // Ambil nilai totalharga dan jmlitem
        const totalharga = parseFloat(totalhargaInput.value);
        const jmlitem = parseFloat(jmlitemInput.value);

        // Hitung harga satuan
        let satuan;
        if (isNaN(jmlitem) || jmlitem <= 0) {
            satuan = 0; // set nilai ke 0 jika jmlitem tidak valid atau kurang dari atau sama dengan 0
        } else {
            satuan = totalharga / jmlitem;
        }

        // Masukkan hasil perhitungan ke input satuan
        satuanInput.value = satuan.toFixed(2);
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