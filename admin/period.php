<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
?>
<?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3 class="text-center">LAPORAN PERIODE TRANSAKSI</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-1"><label for="tanggal">Periode:</label></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" onclick="tampildata()" autocomplete="off"></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan2" id="tgllaporan2" onclick="tampildata()" autocomplete="off">
            </div>

            <div class="col-md-2"><label for="tanggal">Opsi Pesan:</label></div>
            <div class="col-md-2"><select name="opsipesan" class="form-control">
                    <option value='%'>All</option>
                    <option value='dine-in'>Dine-in</option>
                    <option value='go-food'>Go Food</option>
                    <option value='shopee-food'>Shopee Food</option>
                    <option value='grab-food'>Grab Food</option>
                    <option value='take-away'>Take Away</option>
                </select></div>
            <div class="col-md-2"><label for="tanggal">Opsi Bayar:</label></div>
            <div class="col-md-1"><select name="opsibayar" class="form-control">
                    <option value='%'>All</option>
                    <option value='cash'>Cash</option>
                    <option value='qr-code'>QR Code</option>
                </select></div>

        </div>
    </div>
    <hr>
    <button id="export-pdf"><img src="../image/pdfico.png" width='30px' alt="Export to PDF"> Export to PDF</button>
    <button id="export-xls"><img src="../image/excelico.png" width='30px' alt="Export to XLS"> Export to XLS</button>
    <div style="overflow-x:auto;">
        <table id="tblcurentrekapan" table class="table table-bordered shadow" width="100%" cellspacing="0">
            <thead class="bg-dark text-white">
                <th>Tanggal</th>
                <th>Pemesan</th>
                <th>Jml Orang</th>
                <th>Menu</th>
                <th>Resto</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Pokok</th>
                <th>Margin</th>
                <th>Opsi Pesan</th>
                <th>Opsi Bayar</th>
                <th>Kasir</th>
            </thead>

            <tbody id="tblharian"></tbody>
        </table>

    </div>
</div>

<script src="../admin/vendor/jquery/jquery.min.js"></script>
<script src="../admin/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../admin/js/sb-admin.min.js"></script>
<script>
    $(document).ready(function() {
        // load data saat halaman pertama kali di-load
        var kemarin = new Date();
        kemarin.setDate(kemarin.getDate() - 1);
        document.getElementById('tgllaporan').value = kemarin.toISOString().slice(0, 10);
        document.getElementById('tgllaporan2').value = new Date().toISOString().slice(0, 10);



        $("#tblharian").load("displayperiod.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());

        // fungsi untuk memfilter data berdasarkan tanggal
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblharian").load("displayperiod.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());
                $(this).datepicker('hide');
            }
        });
        $('#tgllaporan2').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblharian").load("displayperiod.php?cmd=display&tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val());
                $(this).datepicker('hide');
            }
        });

        $('select[name="storeid"], select[name="opsipesan"], select[name="opsibayar"]').change(function() {
            var tanggal = $('input[name="tgllaporan"]').val();
            var tanggal2 = $('input[name="tgllaporan2"]').val();
            var storeid = $('select[name="storeid"]').val();
            var kategori = $('select[name="opsipesan"]').val();
            var bayar = $('select[name="opsibayar"]').val();

            // mengambil data dari server sesuai dengan kategori, metode pembayaran, dan toko yang dipilih
            $.ajax({
                url: 'displayperiod.php',
                data: {
                    cmd: 'display',
                    tgl: tanggal,
                    tgl2: tanggal2,
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

        window.open("periodepdf.php?tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }

    // Ketika tombol ditekan, panggil fungsi exportToExcel()
    document.getElementById('export-xls').addEventListener('click', exportToXLS);

    function exportToXLS() {

        window.open("periodeexcel.php?tgl=" + $('input[name="tgllaporan"]').val() + "&tgl2=" + $('input[name="tgllaporan2"]').val() + "&storeid=" + $('select[name="storeid"]').val() + "&kategori=" + $('select[name="opsipesan"]').val() + "&bayar=" + $('select[name="opsibayar"]').val());

    }
</script>
</script>

<?php include '../admin/footer.php'; ?>