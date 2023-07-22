<?php
include("../functions.php");


if (empty($_GET['cmd']))
	die();
if ($_GET['cmd'] != 'display')
	die();

if (empty($_GET['tgl'])) {
	$tgl = date('Y-m-d');
} else {
	$tgl = $sqlconnection->real_escape_string($_GET['tgl']);
}



$konsumsiquery = "SELECT a.idinventory, nama, sum(inventoryqty) as konsumsi, unit,idstore, img, confignamaresto, if(a.`status`='ready','used',a.`status`) AS status
FROM `tbl_consumption` a join tbl_inventory b
on a.idinventory=b.idinventory
join tbl_store c on b.idstore=c.storeid
where  cast(inputdate as date)='{$tgl}'
 GROUP BY a.idinventory, nama, a.`status` ORDER BY a.`status`, confignamaresto";

if ($result = $sqlconnection->query($konsumsiquery)) {

	if ($result->num_rows == 0) {
		echo "<td colspan='4'>data konsumsi belum tersedia.</td>";
	}

	$no = 1;

	while ($konsumsi = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr class="text-center">

			<td><?php echo $no++; ?></td>
			<td><?php echo $konsumsi['nama']; ?></td>
			<td><?php echo $konsumsi['konsumsi']; ?></td>
			<td><?php echo $konsumsi['unit']; ?></td>
			<td><img src="../image/<?= $konsumsi['img'] ?>" width="50px"></td>
			<td><strong><?php echo $konsumsi['status']; ?></strong></td>
		</tr> <?php }
		} else {

			echo $sqlconnection->error;

			echo "Something wrong.";
		} ?>