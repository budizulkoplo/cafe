<?php
include("../functions.php");

if (empty($_GET['cmd']))
	die();
if ($_GET['cmd'] != 'display')
	die();

if (empty($_GET['hari'])) {
	$hari = 30;
} else {
	$hari = $sqlconnection->real_escape_string($_GET['hari']);
}

if (empty($_GET['tgl'])) {
	$tgl = date('Y-m-d');
} else {
	$tgl = $sqlconnection->real_escape_string($_GET['tgl']);
}

if (empty($_GET['tgl2'])) {
	$tgl2 = date('Y-m-d');
} else {
	$tgl2 = $sqlconnection->real_escape_string($_GET['tgl2']);
}

if ($_SESSION['storeid'] == 0) {
	$storeid = '%';
} else {
	$storeid = $_SESSION['storeid'];
}
$totalharga = 0;
$totaljumlah = 0;
$totaldiskon = 0;
$totalbayar = 0;
$totalpokok = 0;
$totalmargin = 0;

$konsumsiquery = "SELECT b.itemid, menuItemName, b.price, sum(a.quantity) as totaljual, d.confignamaresto
FROM tbl_orderdetail a join tbl_menuitem b on a.itemID=b.itemID
join tbl_order c on a.orderID=c.orderID
join tbl_store d on b.storeid=d.storeid
where c.status not in('cancelled','expired' )
and b.storeid='{$storeid}' 
and order_date >='{$tgl}' and order_date <='{$tgl2}' 
group by b.itemid, menuItemName
order by sum(a.quantity) desc";

echo $konsumsiquery;
if ($result = $sqlconnection->query($konsumsiquery)) {

	if ($result->num_rows == 0) {
		echo $konsumsiquery;
		echo "<td colspan='4'>data konsumsi belum tersedia.</td>";
	}

	$no = 1;

	while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) { ?>
		<tr class="text-center">
			<td><?php echo $no++; ?></td>
			<td><?php echo $rekapan['menuItemName']; ?></td>
			<td><?php echo $rekapan['price']; ?></td>
			<td><?php echo $rekapan['totaljual']; ?></td>
			<td><?php echo $rekapan['confignamaresto']; ?></td>
		</tr>

<?php
		$orderid = $rekapan['orderid'];
	}
} else {

	echo $sqlconnection->error;

	echo "Something wrong.";
} ?>