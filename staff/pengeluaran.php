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

    <h3>LAPORAN PENGELUARAN HARIAN</h3>
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
        <table class="table table-bordered shadow" width="100%" cellspacing="0">
            <thead>
                <tr class="bg-dark text-white">
                    <th>#</th>
                    <th>Tgl Transaksi</th>
                    <th>Kategori</th>
                    <th>Nama Pengeluaran</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (in_array($_SESSION['user_role'], array("admin", "super admin"))) {
                    $displaystoreQuery = "select tgltransaksi, kategori,namatransaksi, jumlah, kasir, keterangan,
                    (select count(*) from tbl_operasional where 
                     tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan1,
                    (select count(*) from tbl_operasional where tbl_operasional.kategori=TBO.kategori 
                    and tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan2  from tbl_operasional TBO tgltransaksi='{$tanggal}'
                    ORDER BY tgltransaksi, kategori asc";
                } else {
                    $rsid = $_SESSION['storeid'];
                    $displaystoreQuery = "select cast(tgltransaksi as date) tgltransaksi,concat(cast(tgltransaksi as date),kategori) as cator, kategori,namatransaksi, jumlah, kasir, keterangan,
                    (select count(*) from tbl_operasional where 
                     tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan1,
                    (select count(*) from tbl_operasional where tbl_operasional.kategori=TBO.kategori 
                    and tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan2  from tbl_operasional TBO where storeid='{$rsid}' and tgltransaksi>='{$tanggal}' and tgltransaksi<='{$tanggal2}'
                    ORDER BY tgltransaksi, kategori asc";
                    // echo $displaystoreQuery;
                }
                $total = 0;
                if ($result = $sqlconnection->query($displaystoreQuery)) {
                    if ($result->num_rows == 0) {
                        echo "<td colspan='4'>Laporan pengeluaran belum tersedia.</td>";
                    }
                    $rsno = 1;
                    $tgl = "";
                    $kategori = "^&%&*^&^$&";
                    $totalkategori = 0;
                    while ($rs = $result->fetch_array(MYSQLI_ASSOC)) { ?>
                        <?php
                        if ($kategori <> $rs['cator'] and $kategori <> "^&%&*^&^$&") { ?>
                            <tr bgcolor='#F8F9FA' style='font-weight:bold;'>
                                <td colspan="4">SUB TOTAL</td>
                                <td align='right'>Rp. <?php echo number_format($totalkategori, 0, ',', '.'); ?></td>
                                <td></td>
                            </tr>
                        <?php $totalkategori = 0;
                        }
                        ?>
                        <tr align="left">
                            <td><?php echo $rsno++; ?></td>
                            <?php
                            if ($kategori <> $rs['cator']) { ?>
                                <td rowspan="<?= $rs['rowspan2'] ?>"><?php echo $rs['tgltransaksi']; ?></td>
                            <?php }
                            ?>
                            <?php
                            if ($kategori <> $rs['cator']) { ?>
                                <td rowspan="<?= $rs['rowspan2'] ?>"><?php echo $rs['kategori']; ?></td>
                            <?php }
                            ?>
                            <td><?php echo $rs['namatransaksi']; ?></td>
                            <td align='right'>Rp. <?php echo number_format($rs['jumlah'], 0, ',', '.'); ?></td>
                            <td><?php echo $rs['keterangan']; ?></td>

                        </tr> <?php
                                $totalkategori = $totalkategori + $rs['jumlah'];
                                $total = $total + $rs['jumlah'];
                                $tgl = $rs['tgltransaksi'];
                                $kategori = $rs['cator'];
                            }
                        } else {
                            echo $sqlconnection->error;
                            echo "Something wrong.";
                        } ?>
                <tr bgcolor='#F8F9FA' style='font-weight:bold;'>
                    <td colspan="4">SUB TOTAL</td>
                    <td align='right'>Rp. <?php echo number_format($totalkategori, 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
                <tr bgcolor='#F8F9FA' style='font-weight:bold;'>
                    <td align='center' colspan='4'>TOTAL</td>
                    <td align='right'>Rp. <?= number_format($total, 0, ',', '.') ?></td>
                    <td></td>


                </tr>
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
        window.location.href = "pengeluaran.php?cmd=display&tanggal=" + tanggal + "&tanggal2=" + tanggal2;
    }
</script>
<script type="text/javascript">
    // Ketika tombol ditekan, panggil fungsi exportToPdf()
    document.getElementById('export-pdf').addEventListener('click', exportToPdf);

    function exportToPdf() {

        window.open("pengeluaranpdf.php?tgl=" + $('input[name="tgllaporan"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("pengeluaranexcel.php?tgl=" + $('input[name="tgllaporan"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }
</script>