<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
?>

<?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3>LAPORAN GENERAL</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-2"><label for="tanggal">Pilih Tanggal:</label></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" value='<?= $_GET['tanggal']; ?>' autocomplete="off"></div>
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
                    <th>Transaksi</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (!isset($_GET['tanggal'])) {
                    $tanggal = date('Y-m-d');
                } else {
                    $tanggal = $_GET['tanggal'];
                }

                if (in_array($_SESSION['user_role'], array("admin", "super admin"))) {
                    $rsid = '%';
                } else {
                    $rsid = $_SESSION['storeid'];
                }
                $displaystoreQuery = "
                    select 'cashonhand' as Transaksi, (select sum(nominal) from cashonhand where idstore like '{$rsid}') as Nominal
                    union
                    select 'Penjualan Cash' as Transaksi,(select ifnull(sum(jmlbayar),0) from tbl_order where opsibayar='cash' and status in('completed','ready','finish') and cast(order_date as date)='{$tanggal}' and storeid like '{$rsid}') as Nominal
                    union
                    select 'Penjualan QR', (select ifnull(sum(jmlbayar),0) from tbl_order where opsibayar='qr-code' and status in('completed','ready','finish') and cast(order_date as date)='{$tanggal}' and storeid like '{$rsid}')
                    union
                    select 'Pengeluaran', (select sum(jumlah) from tbl_operasional where cast(tgltransaksi as date)='{$tanggal}' and storeid like '{$rsid}') 

                    ";

                $total = 0;
                $pemasukan = 0;
                $pengeluaran = 0;
                $qr = 0;
                $cashonhand = 0;
                if ($result = $sqlconnection->query($displaystoreQuery)) {
                    if ($result->num_rows == 0) {
                        echo "<td colspan='4'>Laporan pengeluaran belum tersedia.</td>";
                    }
                    $rsno = 1;
                    while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
                        if ($rs['Transaksi'] == 'cashonhand') {
                ?>
                            <tr align="left">
                                <td><?php echo $rsno ?></td>
                                <td>Cash on Hand</td>
                                <td align='right'>Rp. <?php echo number_format($rs['Nominal'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                        <?php }

                        if ($rs['Transaksi'] <> 'cashonhand') {
                        ?>
                            <tr align="left">
                                <td><?php echo $rsno++; ?></td>
                                <td><?php echo $rs['Transaksi']; ?></td>
                                <td align='right'>Rp. <?php echo number_format($rs['Nominal'], 0, ',', '.'); ?></td>
                            </tr>
                <?php }
                        if ($rs['Transaksi'] != 'Pengeluaran' && $rs['Transaksi'] != 'cashonhand') {
                            $pemasukan += $rs['Nominal'];
                        }
                        if ($rs['Transaksi'] == 'Pengeluaran') {
                            $pengeluaran = $pengeluaran + $rs['Nominal'];
                        }
                        if ($rs['Transaksi'] == 'Penjualan QR') {
                            $qr = $qr + $rs['Nominal'];
                        }
                        if ($rs['Transaksi'] == 'cashonhand') {
                            $cashonhand = $cashonhand + $rs['Nominal'];
                        }
                    }
                } else {
                    echo $sqlconnection->error;
                    echo "Something wrong.";
                } ?>
                </tr>
                <tr class="bg-light text-dark">
                    <td colspan="2" align="left">SUB TOTAL</td>
                    <td align="right">Rp. <?= number_format($pemasukan - $pengeluaran, 0, ',', '.') ?></td>
                </tr>
                <tr class="bg-light text-dark">
                    <td colspan="2" align="left">SALDO</td>
                    <td align="right">Rp. <?= number_format($pemasukan + $cashonhand - $pengeluaran, 0, ',', '.') ?></td>
                </tr>
                <tr class="bg-light text-dark">
                    <td colspan="2" align="left">SALDO CASH</td>
                    <td align="right">Rp. <?= number_format(($pemasukan + $cashonhand - $pengeluaran) - $qr, 0, ',', '.') ?></td>
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
        window.location.href = "general.php?cmd=display&tanggal=" + tanggal;
    }
</script>
<script type="text/javascript">
    // Ketika tombol ditekan, panggil fungsi exportToPdf()
    document.getElementById('export-pdf').addEventListener('click', exportToPdf);

    function exportToPdf() {

        window.open("generalpdf.php?tgl=" + $('input[name="tgllaporan"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("generalexcel.php?tgl=" + $('input[name="tgllaporan"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }
</script>