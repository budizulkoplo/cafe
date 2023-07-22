<?php
include("../functions.php");
if (isset($_GET['menuid'])) {
	$menuID = $_GET['menuid'];
	$deleteMenuQuery = "Update tbl_menu set active='0' WHERE menuID = {$menuID}";
	// echo $deleteMenuQuery;
	if ($sqlconnection->query($deleteMenuQuery) === TRUE) {
		// echo "hehe";
		header("Location: menu.php");
		exit();
	} else {
		echo "someting wrong";
		echo $sqlconnection->error;
	}
}
