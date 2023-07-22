<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");

if (isset($_POST['status']) && isset($_POST['orderID'])) {

	$status = $sqlconnection->real_escape_string($_POST['status']);
	$orderID = $sqlconnection->real_escape_string($_POST['orderID']);

	$addOrderQuery = "UPDATE tbl_order SET status = '{$status}' WHERE orderID = {$orderID};";

	// masukan koding perhitungan disini
	$consumption = "update tbl_consumption set status='ready'
		where orderid = {$orderID};";

	$updateinventory = "update tbl_inventory tbi set jml=(jml-(select inventoryqty*itemqty from tbl_consumption where tbl_consumption.idinventory=tbi.idinventory and tbl_consumption.orderID = {$orderID}))  
	where idinventory in(
	select idinventory from tbl_consumption where orderid={$orderID});";

	//bataltransaksi


	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		if ($status == "ready") {
			$sqlconnection->query($consumption);
			$sqlconnection->query($updateinventory);
		}
		echo "inserted.";
	} else {
		// 		//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}

if (isset($_GET['orderID'])) {

	$status = "Completed";
	$orderID = $sqlconnection->real_escape_string($_GET['orderID']);

	$addOrderQuery = "UPDATE tbl_order SET status = '{$status}' WHERE orderID = {$orderID};";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		// echo "inserted.";
		header("Location: index.php");
	} else {
		//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}
