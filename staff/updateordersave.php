<?php
include("../functions.php");

if ($_POST['action'] == "update") {
	$orderid = $sqlconnection->real_escape_string($_POST['id']);
	$itemid = $sqlconnection->real_escape_string($_POST['itemid']);
	$qty = $sqlconnection->real_escape_string($_POST['qty']);

	$updateorder = "update tbl_orderdetail set `quantity`='{$qty}' where orderID='{$orderid}' and itemID='{$itemid}' ";
	$updateconsumption = "update tbl_consumption set `itemqty`='{$qty}' where orderid='{$orderid}' and itemid='{$itemid}' ";

	$sqlconnection->query($updateorder);
	$sqlconnection->query($updateconsumption);
}

if ($_POST['action'] == "delete") {
	$orderid = $sqlconnection->real_escape_string($_POST['id']);
	$itemid = $sqlconnection->real_escape_string($_POST['itemid']);

	$deleteorder = "delete from tbl_orderdetail where orderID='{$orderid}' and itemID='{$itemid}' ";
	$deleteconsumption = "delete from tbl_consumption where orderid='{$orderid}' and itemid='{$itemid}' ";

	$sqlconnection->query($deleteorder);
	$sqlconnection->query($deleteconsumption);
}

if ($_POST['action'] == "cetak") {
	$diskon = 0;
	$kembalian = 0;
	$orderid = $sqlconnection->real_escape_string($_POST['id']);
	$total = $sqlconnection->real_escape_string($_POST['total']);
	$diskon = $sqlconnection->real_escape_string($_POST['diskon']);
	$jmlbayar = $sqlconnection->real_escape_string($_POST['total'] - $_POST['diskon']);
	$uangditerima = $sqlconnection->real_escape_string($_POST['bayar']);
	$kembalian = $sqlconnection->real_escape_string($_POST['kembalian']);

	$updatebayar = "update tbl_order set `total`='{$total}', `jmlbayar`='{$jmlbayar}', `diskon`='{$diskon}',  `uangditerima`='{$uangditerima}',  `kembalian`='{$kembalian}' where orderID='{$orderid}' ";

	$sqlconnection->query($updatebayar);
}
