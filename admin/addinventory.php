<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "admin")
	header("Location: login.php");

$username = $_SESSION['username'];
$storeid = $_SESSION['storeid'];

if (isset($_POST['addinventory'])) {
	if (!empty($_POST['storeid']) && !empty($_POST['namaitem'])) {
		$storeid = $sqlconnection->real_escape_string($_POST['storeid']);
		$kategori = $sqlconnection->real_escape_string($_POST['kategori']);
		$namaitem = $sqlconnection->real_escape_string($_POST['namaitem']);
		$jml = $sqlconnection->real_escape_string($_POST['qty']);
		$unit = $sqlconnection->real_escape_string($_POST['satuan']);

		$addstoreQuery = "INSERT INTO tbl_inventory (nama, `jml`, unit, idstore, kategori) VALUES ('{$namaitem}' ,'{$jml}','{$unit}' ,'{$storeid}','{$kategori}') ";

		if ($sqlconnection->query($addstoreQuery) === TRUE) {
			// echo "added.";
			header("Location: inventory.php");
			exit();
		} else {
			//handle
			echo "someting wong";
			echo $sqlconnection->error;
		}
	}
}

if ($_POST['action'] == "update") {
	$itemid = $sqlconnection->real_escape_string($_POST['id']);
	$jml = $sqlconnection->real_escape_string($_POST['jml']);

	$addstoreQuery = "update tbl_inventory set `jml`='{$jml}' where idinventory='{$itemid}' ";

	echo $addstoreQuery;
	$sqlconnection->query($addstoreQuery);
}

if ($_POST['action'] == "opname") {
	$itemid = $sqlconnection->real_escape_string($_POST['id']);
	$jml = $sqlconnection->real_escape_string($_POST['jml']);

	$addstoreQuery = "update tbl_inventory set `opname`='{$jml}' where idinventory='{$itemid}' ";

	echo $addstoreQuery;
	$sqlconnection->query($addstoreQuery);
}

if ($_POST['action'] == "updateopname") {

	$step1 = "insert into tbl_opname(idinventory,namaitem,oldstock,newstock,opnameuser, unit, storeid)
	SELECT idinventory,nama,jml,opname, '{$username}', unit, '{$storeid}' FROM `tbl_inventory` where opname is not null";
	$step2 = "update tbl_inventory set jml=opname where  opname is not null";
	$step3 = "update tbl_inventory set opname=null where  opname is not null";

	$sqlconnection->query($step1);
	$sqlconnection->query($step2);
	$sqlconnection->query($step3);

	echo $step1;
}

if ($_POST['action'] == "add") {
	$itemid = $sqlconnection->real_escape_string($_POST['id']);
	$jml = $sqlconnection->real_escape_string($_POST['jml']);

	$addstoreQuery = "update tbl_inventory set `jml`=jml+{$jml} where idinventory='{$itemid}' ";

	echo $addstoreQuery;
	$sqlconnection->query($addstoreQuery);
}

if ($_GET['action'] == "delete") {
	$itemid = $sqlconnection->real_escape_string($_GET['id']);

	$addstoreQuery = "update tbl_inventory set `active`='0' where idinventory='{$itemid}' ";
	// delete dari komposisi bahan baku
	$addstoreQuery2 = "delete from tbl_detailinventory where idinventory='{$itemid}' ";

	// echo $addstoreQuery;
	$sqlconnection->query($addstoreQuery);
	$sqlconnection->query($addstoreQuery2);
	header("location:inventory.php");
}

if ($_GET['action'] == "updatekategorivalue") {
	$itemid = $sqlconnection->real_escape_string($_GET['id']);
	$kategori = $sqlconnection->real_escape_string($_POST['role']);

	$addstoreQuery = "update tbl_inventory set `kategori`='{$kategori}' where idinventory='{$itemid}' ";


	// echo $addstoreQuery;
	$sqlconnection->query($addstoreQuery);

	header("location:inventory.php");
}
