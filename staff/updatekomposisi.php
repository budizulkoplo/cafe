<?php

include("../functions.php");
if (isset($_POST['namamenu'])) {
    if (!empty($_POST['itemid']) && !empty($_POST['namamenu']) && !empty($_POST['hargamodal'])) {

        $itemid = $sqlconnection->real_escape_string($_POST['itemid']);
        $namamenu = $sqlconnection->real_escape_string($_POST['namamenu']);
        $hargamodal = $sqlconnection->real_escape_string($_POST['hargamodal']);
        $hargajual = $sqlconnection->real_escape_string($_POST['hargajual']);


        $addItemQuery = "update tbl_menuitem set menuItemName='{$namamenu}',
            price={$hargajual}, 
            modal={$hargamodal} where itemid='{$itemid}'";

        $sqlconnection->query($addItemQuery);

        // hapus komposisi sebelumnya
        $existing = "delete from tbl_detailinventory where idmenu='{$itemid}'";
        $sqlconnection->query($existing);

        //masukan komposisi baru 
        $komposisi = $_POST['komposisi'];
        $qty = $_POST['qty'];

        for ($i = 0; $i < count($_POST['komposisi']); $i++) {
            $query = "INSERT INTO tbl_detailinventory (idmenu, idinventory, qty) VALUES ('" . $itemid . "', '" . $komposisi[$i] . "', '" . $qty[$i] . "')";
            echo $query;
            $sqlconnection->query($query);
        }

        echo $query;
        echo "Data berhasil disimpan.";
    }

    //No input handle

    else {
        echo "tidak boleh kosong";
    }
}
