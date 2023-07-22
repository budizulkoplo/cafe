<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
if (!isset($_GET['tanggal'])) {
    $tanggal = date('Y-m-d');
} else {
    $tanggal = $_GET['tanggal'];
}

if (!isset($_GET['tanggal2'])) {
    $tanggal2 = date('Y-m-d');
} else {
    $tanggal2 = $_GET['tanggal2'];
}
?>
<?php include 'header.php'; ?>
<div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3>LAPORAN PERUBAHAN HARGA BAHAN BAKU</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-1"><label for="tanggal">Periode:</label></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" value='<?= $tanggal; ?>' autocomplete="off"></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan2" id="tgllaporan2" value='<?= $tanggal2; ?>' autocomplete="off">
            </div>
            <div class="col-md-2">
                <p align='left'>Kasir: <?php echo $_SESSION['username']; ?></p>
            </div>
            <div class="col-md-2"><button id="export-pdf"><img src="../image/pdfico.png" width='30px' alt="Export to PDF"> Export to PDF</button></div>
            <div class="col-md-2"><button id="export-xls"><img src="../image/excelico.png" width='30px' alt="Export to XLS"> Export to XLS</button></div>
        </div>
    </div>
    <div class="container">
        <table class="table table-bordered shadow" id="item" width="100%" cellspacing="0">
            <thead>
                <tr class="bg-dark text-white">
                    <th>#</th>
                    <th>Tgl Update</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Belanja</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Average Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (in_array($_SESSION['user_role'], array("admin", "super admin"))) {
                    $displaystoreQuery = "SELECT a.idinventory, updatedate as pricedate,kategori,  namainventory, a.unitprice, unit, idstore FROM `tbl_invpriceupdate` a join tbl_inventory b
                    on a.idinventory=b.idinventory
                    where active='1' order by idinventory asc";
                } else {
                    $rsid = $_SESSION['storeid'];
                    $displaystoreQuery = "SELECT a.idinventory, updatedate as pricedate,kategori,  namainventory, a.unitprice, unit,
                    (SELECT
	round( sum( `tbl_invpriceupdate`.`unitprice` ) / count(*), 0 ) 
FROM
	`tbl_invpriceupdate` where  tbl_invpriceupdate.idinventory=a.idinventory and tbl_invpriceupdate.updatedate>='{$tanggal}' and tbl_invpriceupdate.updatedate<='{$tanggal2}') as avgprice,
    (SELECT jumlah FROM
	`tbl_operasional` where  tbl_operasional.namatransaksi=a.namainventory and cast(tbl_operasional.tgltransaksi as date)=cast(a.updatedate as date) limit 1) as jmlbelanja,
	(SELECT jmlitem FROM
	`tbl_operasional` where  tbl_operasional.namatransaksi=a.namainventory and cast(tbl_operasional.tgltransaksi as date)=cast(a.updatedate as date) limit 1) as jmlitem,
     idstore FROM `tbl_invpriceupdate` a join tbl_inventory b
                    on a.idinventory=b.idinventory
                    where active='1' and idstore='{$rsid}' and updatedate>='{$tanggal}' and updatedate<='{$tanggal2}' order by idinventory, updatedate asc";
                    // echo $displaystoreQuery;
                }
                $total = 0;
                $no = 1;
                if ($result = $sqlconnection->query($displaystoreQuery)) {
                    if ($result->num_rows == 0) {
                        echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                    }
                    $rsno = 1;
                    $tgl = "";
                    $kategori = "^&%&*^&^$&";
                    $totalkategori = 0;
                    while ($rs = $result->fetch_array(MYSQLI_ASSOC)) { ?>
                        <tr class="text-center">
                            <td><?php echo $no++; ?></td>
                            <td nowrap align="left"><?php echo $rs['pricedate']; ?></td>
                            <td nowrap align="left"><?php echo $rs['kategori']; ?></td>
                            <td nowrap align="left"><?php echo $rs['namainventory']; ?></td>
                            <td nowrap align="left">Rp. <?php echo number_format($rs['jmlbelanja'], 0, ',', '.')  ?></td>
                            <td nowrap align="left"><?php echo $rs['jmlitem'] . " " . $rs['unit'];; ?></td>
                            <td nowrap align="left">Rp. <?php echo number_format($rs['unitprice'], 0, ',', '.') . " / " . $rs['unit']; ?></td>
                            <td nowrap align="left">Rp. <?php echo number_format($rs['avgprice'], 0, ',', '.') . " / " . $rs['unit']; ?></td>
                        </tr>
                <?php

                    }
                } else {
                    echo $sqlconnection->error;
                    echo "Something wrong.";
                } ?>

            </tbody>
        </table>
    </div>
</div>
</div> <?php include '../admin/footer.php'; ?>

<script>
    $(document).ready(function() {
        // inisialisasi datepicker
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                // panggil fungsi untuk filter data
                var tanggal = $('input[name="tgllaporan"]').val();
                var tanggal2 = $('input[name="tgllaporan2"]').val();
                filterData(tanggal, tanggal2);
            }
        });
        $('#tgllaporan2').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                // panggil fungsi untuk filter data
                var tanggal = $('input[name="tgllaporan"]').val();
                var tanggal2 = $('input[name="tgllaporan2"]').val();
                filterData(tanggal, tanggal2);
            }
        });

        // event onchange pada input tanggal
        $('#tgllaporan').on('change', function() {
            // ambil nilai tanggal yang baru
            var tanggal = $('input[name="tgllaporan"]').val();
            var tanggal2 = $('input[name="tgllaporan2"]').val();
            // panggil fungsi untuk filter data
            filterData(tanggal, tanggal2);
        });

        $('#tgllaporan2').on('change', function() {
            // ambil nilai tanggal yang baru
            var tanggal = $('input[name="tgllaporan"]').val();
            var tanggal2 = $('input[name="tgllaporan2"]').val();
            // panggil fungsi untuk filter data
            filterData(tanggal, tanggal2);
        });

    });

    function filterData(tanggal, tanggal2) {
        // redirect halaman dengan menambahkan parameter tanggal ke URL
        window.location.href = "updateprice.php?cmd=display&tanggal=" + tanggal + "&tanggal2=" + tanggal2;
    }
</script>
<script type="text/javascript">
    // Ketika tombol ditekan, panggil fungsi exportToPdf()
    document.getElementById('export-pdf').addEventListener('click', exportToPdf);

    function exportToPdf() {

        window.open("updatepricepdf.php?tanggal=" + $('input[name="tgllaporan"]').val() + "&tanggal2=" + $('input[name="tgllaporan2"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("updatepriceexcel.php?tanggal=" + $('input[name="tgllaporan"]').val() + "&tanggal2=" + $('input[name="tgllaporan2"]').val());

    }


    $(document).ready(function() {
        $('#item').DataTable({
            'iDisplayLength': 100
        });
    });
</script>