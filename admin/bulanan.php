<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
?>
<?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3 class="text-center">LAPORAN BULANAN</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-1"><label for="tanggal">Bulan:</label></div>
            <div class="col-md-2"><input type="month" class="form-control" id="month-picker" id="bulan" name="bulan" onchange="tampildata()"></div>
        </div>
    </div>
    <hr>
    <button id="export-pdf"><img src="../image/pdfico.png" width='30px' alt="Export to PDF"> Export to PDF</button>
    <button id="export-xls"><img src="../image/excelico.png" width='30px' alt="Export to XLS"> Export to XLS</button>
    <div style="overflow-x:auto;">
        <table id="tblcurentrekapan" table class="table table-bordered shadow" width="100%" cellspacing="0" style="font-size: 10pt;">
            <thead class="bg-dark text-white">
                <th>#</th>
                <th>Tanggal</th>
                <th>Omset</th>
                <th>Diskon Terjual</th>
                <th>Omset Bersih</th>
                <th>Guest</th>
                <th>APC</th>
                <th>Modal Terjual</th>
                <th>RP Margin</th>
                <th>% Margin</th>
                <th>Pengeluaran</th>
                <th>Cash</th>
                <th>Qris</th>
            </thead>

            <tbody id="tblharian"></tbody>
        </table>

    </div>
</div>

<script src="../admin/vendor/jquery/jquery.min.js"></script>
<script src="../admin/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../admin/js/sb-admin.min.js"></script>
<script>
    const bulan = document.querySelector('#bulan');

    function tampildata() {
        $("#tblharian").load("displaybulanan.php?cmd=display&bulan=" + $('input[name="bulan"]').val());
    }


    $(document).ready(function() {
        // load data saat halaman pertama kali di-load
        var d = new Date();
        var bulanSekarang = d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2);
        $('input[name="bulan"]').val(bulanSekarang);

        $("#tblharian").load("displaybulanan.php?cmd=display&bulan=" + $('input[name="bulan"]').val());

        // fungsi untuk memfilter data berdasarkan tanggal

    });
</script>

<script type="text/javascript">
    // Ketika tombol ditekan, panggil fungsi exportToPdf()
    document.getElementById('export-pdf').addEventListener('click', exportToPdf);

    function exportToPdf() {

        window.open("bulananpdf.php?bulan=" + $('input[name="bulan"]').val() + "&storeid=" + $('select[name="storeid"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("bulananexcel.php?bulan=" + $('input[name="bulan"]').val() + "&storeid=" + $('select[name="storeid"]').val());

    }
</script>
</script>

<?php include '../admin/footer.php'; ?>