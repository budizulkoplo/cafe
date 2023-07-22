<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");

if (!empty($_POST['namatransaksi']) && !empty($_POST['jumlah'])) {
	$tgltransaksi = $sqlconnection->real_escape_string($_POST['tgltransaksi']);
	$kategori = $sqlconnection->real_escape_string($_POST['kategori']);
	$namatransaksi = $sqlconnection->real_escape_string($_POST['namatransaksi']);
	$jumlah = $sqlconnection->real_escape_string($_POST['jumlah']);
	$keterangan = $sqlconnection->real_escape_string($_POST['keterangan']);
	$kasir = $_SESSION['username'];
	$storeid = $_SESSION['storeid'];

	$addtransaksi = "INSERT INTO tbl_operasional (tgltransaksi, kategori, namatransaksi,jumlah, keterangan, kasir ,storeid) VALUES ('{$tgltransaksi}','{$kategori}' ,'{$namatransaksi}' ,'{$jumlah}','{$keterangan}' ,'{$kasir}','{$storeid}') ";
	// echo $addtransaksi ;

	if ($sqlconnection->query($addtransaksi) === TRUE) {
		header("Location: operasional.php");
		exit();
	} else {
		//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}
