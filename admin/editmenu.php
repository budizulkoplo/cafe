<?php include("../functions.php");
include("addmenu.php");
include("additem.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");
if ($_SESSION['user_level'] != "admin") header("Location: login.php"); ?><?php include 'header.php';

                                                                            $idmenu = $sqlconnection->real_escape_string($_GET['idmenu']);
                                                                            $updateItemQuery = "select * from tbl_menuitem a left join tbl_detailinventory b on a.itemID=b.idmenu join tbl_store c on a.storeid=c.storeid WHERE a.itemid = '{$idmenu}'";
                                                                            $data = $sqlconnection->query($updateItemQuery);
                                                                            $row = mysqli_fetch_assoc($data);
                                                                            ?>
<div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white">
    <h3 align="center"><strong>Update Menu</strong></h3>
    <div align="center" class="container">
        <div class="col-md-6">
            <a href="menu.php">
                <button class="form-control btn btn-success btn-block" type="button"><strong>KEMBALI</strong></button>
            </a>
        </div>
    </div>
    <form method="post">
        <div class="container">
            <div class="row">
                <div align="center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_menu">Resto:</label>
                            <select name="storeid" id="storeid" class="form-control" disabled>
                                <option value="<?php echo $row['storeid']; ?>"><?php echo $row['desc']; ?></option>
                                <?php $roleQuery = "SELECT * FROM tbl_store";
                                if ($res = $sqlconnection->query($roleQuery)) {
                                    if ($res->num_rows == 0) {
                                        echo "no role";
                                    }
                                    while ($role = $res->fetch_array(MYSQLI_ASSOC)) {
                                        echo "<option value='" . $role['storeid'] . "'>" . ucfirst($role['desc']) . "</option>";
                                    }
                                } ?>
                            </select>

                        </div>

                        <div class=" form-group">

                            <label for="nama_menu">Nama Menu:</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu" value="<?php echo $row['menuItemName']; ?>">
                            <input type="hidden" class="form-control" id="itemid" name="itemid" value="<?php echo $row['itemID']; ?>">

                        </div>



                        <div class="form-group">

                            <label for="harga_modal">Harga Modal:</label>

                            <input type="text" class="form-control" id="harga_modal" name="harga_modal" value="<?php echo $row['modal']; ?>">

                        </div>

                        <div class="form-group">

                            <label for="harga_jual">Harga Jual:</label>

                            <input type="text" class="form-control" id="harga_jual" name="harga_jual" value="<?php echo $row['price']; ?>">

                        </div>
                        <div class="form-group">

                            <label for="harga_jual">Gambar:</label>

                            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>

                        </div>
                        <br>

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

                                            <input type="text" class="form-control" id="cari">

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
                                    <a class="btnHapus btn-sm btn btn-warning"><span class="glyphicon glyphicon-minus"> Hapus Resep</span></a>
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

<script>
    var datakomposisi = [],
        data = [];

    $("#storeid").change(function() {
        var storeid = $(this).val();
        loadResepAwal();
    });

    function loadResepAwal(arr) {
        // console.log(arr);
        html1 = "ambil=editkomposisi" + "&storeid=" + $("#storeid").val() + "&menuid=" + $("#menuid").val();
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
        html2 = "ambil=editkomposisiakhir" + "&storeid=" + $("#storeid").val() + "&menuid=" + $("#itemid").val();
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
        datakomposisi.push($("input[name=komposisiterpilih]").val());

    }

    function resetResep(arr) {
        html2 = "ambil=resetkomposisiakhir" + "&storeid=" + $("#storeid").val() + "&menuid=" + $("#itemid").val();
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
        datakomposisi.push($("input[name=komposisiterpilih]").val());

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


        $(".btnHapus").click(function() {
            val = $("input[name=komposisiterpilih]:checked").val();
            datakomposisi.splice(datakomposisi.indexOf(val), 1);
            loadResepAwal(datakomposisi);
            resetResep(datakomposisi);
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

                var formData = new FormData();
                $.each(datakomposisi, function(index, value) {
                    if (no3 > 0) {
                        formData.append("storeid", document.getElementById("storeid").value);
                        formData.append("itemid", document.getElementById("itemid").value);
                        formData.append("namamenu", document.getElementById("nama_menu").value);
                        formData.append("hargamodal", document.getElementById("harga_modal").value);
                        formData.append("hargajual", document.getElementById("harga_jual").value);
                        formData.append("komposisi[" + no3 + "]", $($("input[name='komposisiterpilih']")[index]).val());
                        formData.append("qty[" + no3 + "]", $($("input[name='qty']")[index]).val());
                        formData.append("gambar", $("#gambar")[0].files[0]);
                    } else {
                        formData.append("storeid", document.getElementById("storeid").value);
                        formData.append("itemid", document.getElementById("itemid").value);
                        formData.append("namamenu", document.getElementById("nama_menu").value);
                        formData.append("hargamodal", document.getElementById("harga_modal").value);
                        formData.append("hargajual", document.getElementById("harga_jual").value);
                        formData.append("komposisi[" + no3 + "]", $("input[name='komposisiterpilih']").val());
                        formData.append("qty[" + no3 + "]", $("input[name='qty']").val());
                        formData.append("gambar", $("#gambar")[0].files[0]);
                    }
                    no3++;
                });

                $.ajax({
                    method: "POST",
                    url: "updatekomposisi.php",
                    data: formData,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    success: function() {
                        swal({
                            title: "Info",
                            text: "Menu tersimpan",
                        });
                        window.location.href = "menu.php";
                    }
                });

            }
        })


        $("#cari").keyup(function() {
            console.log($("#cari").val());
            Carikomposisi($("#cari").val());
        })

    })
</script>



<?php include 'footer.php'; ?>