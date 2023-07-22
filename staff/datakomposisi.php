<?php

include("../functions.php");



//Add new menu item

if (isset($_POST['namamenu'])) {
    if (!empty($_POST['storeid']) && !empty($_POST['namamenu']) && !empty($_POST['hargamodal'])) {
        $storeid = $sqlconnection->real_escape_string($_POST['storeid']);
        $namamenu = $sqlconnection->real_escape_string($_POST['namamenu']);
        $hargamodal = $sqlconnection->real_escape_string($_POST['hargamodal']);
        $hargajual = $sqlconnection->real_escape_string($_POST['hargajual']);
        $menuid = $sqlconnection->real_escape_string($_POST['menuid']);

        $addItemQuery = "INSERT INTO tbl_menuitem (menuID ,menuItemName ,price, storeid, modal) VALUES ({$menuid} ,'{$namamenu}' ,{$hargajual},{$storeid},{$hargamodal})";
        // header("Location: menu.php");
        $sqlconnection->query($addItemQuery);

        $komposisi = $_POST['komposisi'];
        $qty = $_POST['qty'];

        $itemid = "SELECT MAX(itemid) as itemid FROM tbl_menuitem";
        $result = $sqlconnection->query($itemid);
        $row = mysqli_fetch_assoc($result);
        $id = $row['itemid'];

        for ($i = 0; $i < count($_POST['komposisi']); $i++) {
            $query = "INSERT INTO tbl_detailinventory (idmenu, idinventory, qty) VALUES ('" . $id . "', '" . $komposisi[$i] . "', '" . $qty[$i] . "')";
            echo $query;
            $sqlconnection->query($query);
        }

        echo "Data berhasil disimpan.";
    }

    //No input handle

    else {
        echo "tidak boleh kosong";
    }
}
