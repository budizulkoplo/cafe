<?php
include("../functions.php");

if ($_POST['action'] == "update") {
	$id = $sqlconnection->real_escape_string($_POST['id']);
	$jml = $sqlconnection->real_escape_string($_POST['jml']);

	$cash = "update cashonhand set `nominal`=$jml where idstore='{$id}' ";

	echo $cash;
	$sqlconnection->query($cash);
}
