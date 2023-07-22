<?php include("../functions.php");
include("addmenu.php");
include("additem.php");
include("deletemenu.php");
if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");
if ($_SESSION['user_role'] != "kitchen") header("Location: login.php"); ?><?php include 'header.php'; ?>
<div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white">
    <h3 align="center"><strong>Menu Input</strong></h3>
    <form method="post">
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

                            <label for="nama_menu">Nama Menu:</label>

                            <input type="text" class="form-control" id="nama_menu" name="nama_menu">

                            <input type="hidden" class="form-control" id="categoryid" name="categoryid" value="<?php echo $_GET['categoryid']; ?>">

                            <input type="hidden" class="form-control" id="menuid" name="menuid" value="<?php echo $_GET['menuid']; ?>">

                        </div>



                        <div class="form-group">

                            <label for="harga_modal">Harga Modal:</label>

                            <input type="text" class="form-control" id="harga_modal" name="harga_modal">

                        </div>

                        <div class="form-group">

                            <label for="harga_jual">Harga Jual:</label>

                            <input type="text" class="form-control" id="harga_jual" name="harga_jual">

                        </div>

                    </div>

                </div>

                <hr>

                <!-- mulai komposisi -->

                <div class="row">

                    <div class="col-md-6">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <h4>Bahan Baku</h4>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-xs-12">

                                        <div class="input-group">

                                            <div class="input-group-addon">

                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>

                                            </div>

                                            <input type="text" class="form-control" id="cari" placeholder="Cari menu...">

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-xs-12" style="padding-top:10px;height:250px; overflow:scroll;" id="komposisi"></div>

                                </div>

                            </div>

                            <div class="panel-footer">
                                <div class="btn-group">
                                    <a class="btnTambah btn-sm btn btn-primary"><span class="glyphicon glyphicon-plus"> Tambah Resep</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4>Komposisi Yang sudah ditambahkan</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12" style="height:250px; overflow:scroll;" id="komposisiterpilih"></div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="btn-group">
                                    <a class="btnKembali btn-sm btn btn-warning"><span class="glyphicon glyphicon-minus"> Hapus Resep</span></a>
                                    <a class="btnSubmit btn-sm btn btn-success"><span class="glyphicon glyphicon-save"> Simpan</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- -- tutup row -->
            </div>
    </form>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    var datakomposisi = [],
        data = [];

    $("#storeid").change(function() {
        var storeid = $(this).val();
        loadResepAwal();
    });

    function loadResepAwal(arr) {
        // console.log(arr);
        html1 = "ambil=komposisiawal" + "&storeid=" + $("#storeid").val();
        no = 0;
        $.each(arr, function(index, value) {
            html1 += "&komposisi[" + no + "]=" + value;
            no++;
        })
        $.ajax({
            method: "POST",
            url: "datacondiment.php",
            data: html1,
            success: function(msg) {
                $("#komposisi").html(msg);
            }
        })
    }

    function loadResepAkhir(arr) {
        html2 = "ambil=komposisiakhir";
        no1 = 0;
        $.each(arr, function(index, value) {
            html2 += "&komposisi[" + no1 + "]=" + value;
            no1++;
        })

        $.ajax({
            method: "POST",
            url: "datacondiment.php",
            data: html2,
            success: function(msg) {
                $("#komposisiterpilih").html(msg);
            }
        })
    }

    function Carikomposisi(str) {
        max = $("#komposisi").find("div").length

        for (i = 0; i < max; i++) {
            strObj = $("#komposisi").find("div")[i];

            if ($(strObj).html().toLowerCase().indexOf(str.toLowerCase()) < 0) {
                $(strObj).hide();
            } else {
                $(strObj).show();
            }
        };
    }

    $(function() {
        loadResepAwal();
        loadResepAkhir();


        $(".btnTambah").click(function() {
            if ($("input[name=komposisi]:checked").val() === undefined) {
                swal({
                    title: "Pilih komposisi",
                    text: "",
                    type: "warning"
                });

            } else if ($.inArray($("input[name=komposisi]:checked").val(), datakomposisi) > -1) {
                swal({
                    title: "komposisi sudah dipilih",
                    text: "",
                    type: "warning"
                });
            } else {
                datakomposisi.push($("input[name=komposisi]:checked").val());
                loadResepAwal(datakomposisi);
                loadResepAkhir(datakomposisi);
                $("#cari").val("");
            }

        });


        $(".btnKembali").click(function() {
            val = $("input[name=komposisiterpilih]:checked").val();
            datakomposisi.splice(datakomposisi.indexOf(val), 1);
            loadResepAwal(datakomposisi);
            loadResepAkhir(datakomposisi);
        });



        $(".btnSubmit").click(function() {
            if (datakomposisi.length == 0) {
                swal({
                    title: "Pilih komposisi minimal satu.",
                    text: "",
                    type: "warning"
                });

            } else {
                itemid = "";
                menuid = "";
                storeid = "";
                namamenu = "";
                hargamodal = "";
                hargajual = "";
                html3 = "";
                qty = "";
                no3 = 0;

                $.each(datakomposisi, function(index, value) {
                    if (no3 > 0) {
                        itemid += "&itemid=" + document.getElementById("categoryid").value;
                        menuid += "&menuid=" + document.getElementById("menuid").value;
                        storeid += "&storeid=" + document.getElementById("storeid").value;
                        namamenu += "&namamenu=" + document.getElementById("nama_menu").value;
                        hargamodal += "&hargamodal=" + document.getElementById("harga_modal").value;
                        hargajual += "&hargajual=" + document.getElementById("harga_jual").value;
                        html3 += "&komposisi[" + no3 + "]=" + value;
                        qty += "&qty[" + no3 + "]=" + $("input[name='qty']")[index].value;
                    } else {
                        itemid += "itemid=" + document.getElementById("categoryid").value;
                        menuid += "menuid=" + document.getElementById("menuid").value;
                        storeid += "storeid=" + document.getElementById("storeid").value;
                        namamenu += "namamenu=" + document.getElementById("nama_menu").value;
                        hargamodal += "hargamodal=" + document.getElementById("harga_modal").value;
                        hargajual += "hargajual=" + document.getElementById("harga_jual").value;
                        html3 += "komposisi[" + no3 + "]=" + value;
                        qty += "qty[" + no3 + "]=" + $("input[name='qty']").val();
                    }
                    no3++;
                })
                $.ajax({
                    method: "POST",
                    url: "datakomposisi.php",
                    data: "ambil=submitkomposisi&" + storeid + "&" + itemid + "&" + menuid + "&" + namamenu + "&" + hargamodal + "&" + hargajual + "&" + html3 + "&" + qty,
                    success: function() {
                        // window.location.href = "menu.php";
                        swal({
                            title: "Info",
                            text: "Menu tersimpan",
                        });
                        window.location.href = "menu.php";
                    }
                })
            }
        })


        $("#cari").keyup(function() {
            console.log($("#cari").val());
            Carikomposisi($("#cari").val());
        })

    })
</script>



<?php include 'footer.php'; ?>