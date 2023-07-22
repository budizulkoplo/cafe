<?php

include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

//Deleting Item
if (isset($_GET['rsID'])) {

	$delid = $sqlconnection->real_escape_string($_GET['rsID']);

	$deleteStaffQuery = "DELETE FROM tbl_operasional WHERE idoperasional = {$delid}";

	if ($sqlconnection->query($deleteStaffQuery) === TRUE) {

		header("Location: operasional.php");
		exit();
	} else {
		//handle
		echo "someting wrong";
		echo $sqlconnection->error;
	}
	//echo "<script>alert('{$del_menuID} & {$del_itemID}')</script>";
}
