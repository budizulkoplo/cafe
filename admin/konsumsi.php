<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: login.php"); ?> <?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h1 class="text-center">Laporan Konsumsi Bahan Baku</h1>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-2"><label for="tanggal">Pilih Tanggal:</label></div>
            <div class="col-md-6"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" onclick="tampildata()" autocomplete="off"></div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <br>
        <table id="tblCurrentOrder" table class="table table-responsive table-lg shadow" width="100%" cellspacing="0">
            <thead class="bg-dark text-white">
                <th>#</th>
                <th>Nama Bahan Baku</th>
                <th>Jml Konsumsi</th>
                <th>Satuan</th>
                <th>Store</th>
                <th>Status</th>
            </thead>

            <tbody id="tblkonsumsi"></tbody>
        </table>

    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script type="text/javascript">
    $("#tblkonsumsi").load("displaykonsumsi.php?cmd=display");
</script>
<script>
    function tampildata(tgl) {
        var tgl = $(this).val();
        $("#tblkonsumsi").load("displaykonsumsi.php?cmd=display");
    };

    $(document).ready(function() {
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblkonsumsi").load("displaykonsumsi.php?cmd=display&tgl=" + $(this).val());
                $(this).datepicker('hide');
            }
        });
    });
</script>

<?php include 'footer.php'; ?>