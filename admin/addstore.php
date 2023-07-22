<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "admin")
	header("Location: login.php");

if (isset($_POST['addstore'])) {
	if (!empty($_POST['storename']) && !empty($_POST['desc'])) {
		$storename = $sqlconnection->real_escape_string($_POST['desc']);
		$confignama = $sqlconnection->real_escape_string($_POST['confignama']);
		$configalamat = $sqlconnection->real_escape_string($_POST['configalamat']);
		$confignotelp = $sqlconnection->real_escape_string($_POST['confignotelp']);
		$catatan = $sqlconnection->real_escape_string($_POST['catatan']);



		$logo = $_FILES["logo"]["name"];

		$target = "../image/" . basename($logo);

		$addstoreQuery = "INSERT INTO tbl_store (storename, `desc`, confignamaresto, configalamat, confignotelp,catatan,img) VALUES ('{$storename}' ,'{$desc}','{$confignama}','{$configalamat}','{$confignotelp}','{$catatan}','$logo') ";

		// Move the uploaded file to the designated folder
		move_uploaded_file($_FILES["logo"]["tmp_name"], $target);

		if ($sqlconnection->query($addstoreQuery) === TRUE) {
			echo "added.";
			header("Location: resto.php");
			exit();
		} else {
			//handle
			echo "someting wong";
			echo $sqlconnection->error;
		}
	}
}
