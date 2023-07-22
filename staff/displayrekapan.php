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
if (empty($_GET['kategori'])) {
	$kategori = "%";
} else {
	$kategori = $sqlconnection->real_escape_string($_GET['kategori']);
}
if (empty($_GET['bayar'])) {
	$bayar = "%";
} else {
	$bayar = $sqlconnection->real_escape_string($_GET['bayar']);
}
if (empty($_GET['storeid'])) {
	$storeid = "%";
} else {
	$storeid = $sqlconnection->real_escape_string($_GET['storeid']);
}
$kasirstoreid = $_SESSION['storeid'];



$totalharga = 0;
$totaljumlah = 0;
$totaldiskon = 0;
$totalbayar = 0;
$rekapanquery = "select 
order_date, a.orderid,ordername, jmlorang, menuItemName as namamenu,(select img from tbl_store where storeid=d.storeid) as restoitem,
 price, quantity, (price*quantity) as total, diskon, jmlbayar, opsipesanan, opsibayar, `desc` as storename, img,
 (select count(*) from tbl_orderdetail where itemID in((select itemID from tbl_menuitem where storeid like '{$storeid}'  and itemID in(
    SELECT DISTINCT(itemID) FROM tbl_orderdetail WHERE tbl_orderdetail.orderID = c.orderID ))
	) and tbl_orderdetail.orderID=a.orderID)	as rowspan  from tbl_order a join tbl_store b
on a.storeid=b.storeid join tbl_orderdetail c on a.orderID=c.orderID
join tbl_menuitem d on c.itemID=d.itemID
where  cast(order_date as date)='{$tgl}' and opsipesanan like '{$kategori}' and opsibayar like '{$bayar}'and d.storeid like '{$storeid}' and status in('completed','ready','finish')
and a.storeid='{$kasirstoreid}' 
order by a.orderid asc";



if ($result = $sqlconnection->query($rekapanquery)) {

	if ($result->num_rows == 0) {
		echo $rekapanquery;
		echo "<td colspan='4'>data rekapan belum tersedisa.</td>";
	}

	$no = 1;
	$orderid = 0;

	while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) { ?>
		<tr class="text-center">
			<td><?php echo $no++; ?></td>
			<?php
			if ($orderid <> $rekapan['orderid']) { ?>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><strong><?php echo $rekapan['orderid'] . ' - ' . $rekapan['ordername']; ?></strong></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['jmlorang']; ?></td>
			<?php }
			?>
			<td nowrap align='left'><?php echo $rekapan['namamenu']; ?> | <img src="../image/<?= $rekapan['restoitem'] ?>" width="50px"> </td>
			<?php
			if ($orderid <> $rekapan['orderid']) { ?>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><img src="../image/<?= $rekapan['img'] ?>" width="50px"></td>
			<?php }
			?>
			<td align='right'>Rp. <?php echo number_format($rekapan['price'], 0, ',', '.'); ?></td>
			<td><?php echo $rekapan['quantity']; ?></td>
			<td align='right'>Rp. <?php echo number_format($rekapan['total'], 0, ',', '.'); ?></td>
			<?php
			if ($orderid <> $rekapan['orderid']) { ?>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered">Rp. <?php echo number_format($rekapan['diskon'], 0, ',', '.'); ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered">Rp. <?php echo number_format($rekapan['jmlbayar'], 0, ',', '.'); ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['opsipesanan']; ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['opsibayar']; ?></td>
			<?php }
			$totalharga = $totalharga + $rekapan['price'];
			$totaljumlah = $totaljumlah + $rekapan['total'];
			if ($orderid <> $rekapan['orderid']) {
				$totaldiskon = $totaldiskon + $rekapan['diskon'];
				$totalbayar = $totalbayar + $rekapan['jmlbayar'];
			}
			?>


		</tr>
<?php
		$orderid = $rekapan['orderid'];
	}
} else {

	echo $sqlconnection->error;

	echo "Something wrong.";
} ?>
<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
	<td align='center' colspan='5'>TOTAL</td>
	<td nowrap align='right'>Rp. <?= number_format($totalharga, 0, ',', '.') ?></td>
	<td></td>
	<td nowrap align='right'>Rp. <?= number_format($totaljumlah, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totaldiskon, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totalbayar, 0, ',', '.') ?></td>
	<td colspan="2"></td>

</tr>