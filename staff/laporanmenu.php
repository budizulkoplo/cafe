<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

// if ($_SESSION['user_level'] != "admin") header("Location: login.php"); 
?>
<?php include 'header.php'; ?>
<div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white text-center">

    <h3 class="text-center">REKAP TRANSAKSI HARIAN</h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-2"><label for="tanggal">Pilih Tanggal:</label></div>
            <div class="col-md-2"><input type="text" class="datepicker form-control" name="tgllaporan" id="tgllaporan" onclick="tampildata()" autocomplete="off"></div>

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
            <div class="col-md-1"> <select name="storeid" class="form-control">
                    <option value='%'>All</option>
                    <?php $roleQuery = "SELECT storeid, `desc` FROM tbl_store";
                    if ($res = $sqlconnection->query($roleQuery)) {
                        while ($id = $res->fetch_array(MYSQLI_ASSOC)) {
                            echo "<option value='" . $id['storeid'] . "'>" . ucfirst($id['desc']) . "</option>";
                        }
                    } ?>
                </select></div>
        </div>
    </div>
    <div class="card-body text-center">
        <table id="tblcurentrekapan" table class="table table-bordered shadow" width="100%" cellspacing="0">
            <thead class="bg-dark text-white">
                <th>#</th>
                <th>Pemesan</th>
                <th>Jml Orang</th>
                <th>Menu</th>
                <th>Resto</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Opsi Pesan</th>
                <th>Opsi Bayar</th>
            </thead>

            <tbody id="tblrekapan"></tbody>
        </table>

    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script>
    $(document).ready(function() {
        // load data saat halaman pertama kali di-load
        $("#tblrekapan").load("displaysellmenu.php?cmd=display");

        // fungsi untuk memfilter data berdasarkan tanggal
        $('#tgllaporan').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $("#tblrekapan").load("displaysellmenu.php?cmd=display&tgl=" + $(this).val());
                $(this).datepicker('hide');
            }
        });

        $('select[name="storeid"], select[name="opsipesan"], select[name="opsibayar"]').change(function() {
            var tanggal = $('select[name="tgllaporan"]').val();
            var storeid = $('select[name="storeid"]').val();
            var kategori = $('select[name="opsipesan"]').val();
            var bayar = $('select[name="opsibayar"]').val();

            // mengambil data dari server sesuai dengan kategori, metode pembayaran, dan toko yang dipilih
            $.ajax({
                url: 'displaysellmenu.php',
                data: {
                    cmd: 'display',
                    tanggal: tanggal,
                    kategori: kategori,
                    bayar: bayar,
                    storeid: storeid
                },
                type: 'GET',
                success: function(response) {
                    // mengganti isi tabel dengan data yang diambil dari server
                    $('#tblrekapan').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

    });
</script>

<?php include '../admin/footer.php'; ?>