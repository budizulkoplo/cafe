<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
    header("Location: login.php");


if (!empty($_POST['kategori']) && !empty($_POST['nama'])) {
    $storeid = $sqlconnection->real_escape_string($_POST['storeid']);
    $itemid = $sqlconnection->real_escape_string($_POST['id']);
    $tgltransaksi = $sqlconnection->real_escape_string($_POST['tgltransaksi']);
    $kategori = $sqlconnection->real_escape_string($_POST['kategori']);
    $namatransaksi = $sqlconnection->real_escape_string($_POST['nama']);
    $jmlitem = $sqlconnection->real_escape_string($_POST['jmlitem']);
    $satuannya = $sqlconnection->real_escape_string($_POST['satuannya']);
    $jumlah = $sqlconnection->real_escape_string($_POST['totalharga']);
    $keterangan = $sqlconnection->real_escape_string($_POST['ket']);
    $unitprice = $sqlconnection->real_escape_string($_POST['satuan']);
    $kasir = $_SESSION['username'];
    $storeid = $_SESSION['storeid'];

    $addtransaksi = "INSERT INTO tbl_operasional (tgltransaksi, kategori, namatransaksi, jumlah, keterangan, kasir, storeid, jmlitem,hargasatuan, satuan) VALUES ('{$tgltransaksi}', '{$kategori}' ,'{$namatransaksi}' ,'{$jumlah}','{$keterangan}' ,'{$kasir}','{$storeid}','{$jmlitem}','{$unitprice}','{$satuannya}') ";
    $updatestock = "Update tbl_inventory set jml=(jml+{$jmlitem}), unitprice={$unitprice} where idinventory='{$itemid}'";

    // tambahan
    $updateprice = "INSERT INTO tbl_invpriceupdate(idinventory,namainventory,unitprice) VALUES ('{$itemid}','{$namatransaksi}',{$unitprice})";
    $belanja = "INSERT INTO tbl_belanja (namabelanja, jmlitem, totalharga, hargasatuan, satuan, storeid, idinventory) VALUES ('{$namatransaksi}' ,'{$jmlitem}','{$jumlah}' ,'{$unitprice}','{$satuannya}','{$storeid}','{$itemid}') ";

    if ($sqlconnection->query($addtransaksi) === TRUE) {
        $sqlconnection->query($updatestock);
        $sqlconnection->query($updateprice);
        $sqlconnection->query($belanja);
        header("Location: inventory.php");
        exit();
    } else {
        //handle
        echo "someting wong";
        echo $sqlconnection->error;
    }
}
