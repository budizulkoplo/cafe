<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
?>
<?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3 class="text-center">TOP SELL ITEM</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-1"><label for="tanggal">Periode:</label></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" onclick="tampildata()" autocomplete="off"></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan2" id="tgllaporan2" onclick="tampildata()" autocomplete="off">
            </div>

        </div>
    </div>
    <hr>
    <button id="export-pdf"><img src="../image/pdfico.png" width='30px' alt="Export to PDF"> Export to PDF</button>
    <button id="export-xls"><img src="../image/excelico.png" width='30px' alt="Export to XLS"> Export to XLS</button>
    <div style="overflow-x:auto;">
        <table id="tblcurentrekapan" table class="table table-bordered shadow" width="100%" cellspacing="0">
            <thead class="bg-dark text-white">
                <th>#</th>
                <th>Menu</th>
                <th>Price</th>
                <th>Total Terjual</th>
                <th>Resto</th>
            </thead>

            <tbody id="tblharian"></tbody>
        </table>

    </div>
</div>

<script src="../admin/vendor/jquery/jquery.min.js"></script>
<script src="../admin/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../admin/js/sb-admin.min.js"></script>
<script>
    function tampildata() {
        $("#tblharian").load("displaytopsell.php?cmd=display&hari=" + $('input[name="jmlhari"]').val());
    }


    $(document).ready(function() {
        // load data saat halaman pertama kali di-load

        var kemarin = new Date();
        kemarin.setDate(kemarin.getDate() - 1);
        document.getElementById('tgllaporan').value = kemarin.toISOString().slice(0, 10);
        document.getElementById('tgllaporan2').value = new Date().toISOString().slice(0, 10);



        $("#tblharian").load("displaytopsell.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());

        // fungsi untuk memfilter data berdasarkan tanggal
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblharian").load("displaytopsell.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());
                $(this).datepicker('hide');
            }
        });
        $('#tgllaporan2').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblharian").load("displaytopsell.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());
                $(this).datepicker('hide');
            }
        });

        $('select[name="storeid"], select[name="opsipesan"], select[name="opsibayar"]').change(function() {
            var tanggal = $('input[name="tgllaporan"]').val();
            var storeid = $('select[name="storeid"]').val();
            var kategori = $('select[name="opsipesan"]').val();
            var bayar = $('select[name="opsibayar"]').val();

            // mengambil data dari server sesuai dengan kategori, metode pembayaran, dan toko yang dipilih
            $.ajax({
                url: 'displaytopsell.php',
                data: {
                    cmd: 'display',
                    tgl: tanggal,
                    kategori: kategori,
                    bayar: bayar,
                    storeid: storeid
                },
                type: 'GET',
                success: function(response) {
                    // mengganti isi tabel dengan data yang diambil dari server
                    $('#tblharian').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

    });
</script>

<script type="text/javascript">
    // Ketika tombol ditekan, panggil fungsi exportToPdf()
    document.getElementById('export-pdf').addEventListener('click', exportToPdf);

    function exportToPdf() {

        window.open("topsellpdf.php?tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("topsellexcel.php?tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());

    }
</script>
</script>

<?php include '../admin/footer.php'; ?>