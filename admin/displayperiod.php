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

if (empty($_GET['tgl2'])) {
	$tgl2 = date('Y-m-d');
} else {
	$tgl2 = $sqlconnection->real_escape_string($_GET['tgl2']);
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

$konsumsiquery = "select 
order_date, a.orderid,ordername, jmlorang, menuItemName as namamenu, price, quantity, (price*quantity) as total, opsipesanan, opsibayar, `desc` as storename, img,
( SELECT count(itemID) FROM tbl_orderdetail WHERE tbl_orderdetail.orderID = c.orderID and itemid in(select itemID from tbl_menuitem) ) rowspan,
(modal*quantity) as hargapokok, (modal*quantity) as hargapokok, ((price*quantity)-(modal*quantity)) as margin, diskon,jmlbayar, kasir
from tbl_order a join tbl_store b
on a.storeid=b.storeid join tbl_orderdetail c on a.orderID=c.orderID
join tbl_menuitem d on c.itemID=d.itemID
where  cast(order_date as date)>='{$tgl}' and cast(order_date as date)<='{$tgl2}' and opsipesanan like '{$kategori}' and opsibayar like '{$bayar}'
and a.storeid like '{$storeid}' and status in('completed','ready','finish')
order by a.orderid asc";

// echo $konsumsiquery;
if ($result = $sqlconnection->query($konsumsiquery)) {

	if ($result->num_rows == 0) {
		echo $konsumsiquery;
		echo "<td colspan='4'>data konsumsi belum tersedia.</td>";
	}

	$no = 1;

	while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) { ?>
		<tr class="text-center">
			<td nowrap align='left'><?php echo $rekapan['order_date']; ?></td>
			<?php
			if ($orderid <> $rekapan['orderid']) { ?>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><strong><?php echo $rekapan['orderid'] . ' - ' . $rekapan['ordername']; ?></strong></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['jmlorang']; ?></td>
			<?php }
			?>
			<td nowrap align='left'><?php echo $rekapan['namamenu']; ?></td>
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
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered" align='right'>Rp. <?php echo number_format($rekapan['diskon'], 0, ',', '.'); ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered" align='right'>Rp. <?php echo number_format($rekapan['jmlbayar'], 0, ',', '.'); ?></td>
			<?php }
			?>
			<td align='right'>Rp. <?php echo number_format($rekapan['hargapokok'], 0, ',', '.'); ?></td>
			<td align='right'>Rp. <?php echo number_format($rekapan['margin'], 0, ',', '.'); ?></td>
			<?php
			if ($orderid <> $rekapan['orderid']) { ?>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['opsipesanan']; ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['opsibayar']; ?></td>
				<td rowspan="<?= $rekapan['rowspan'] ?>" class="centered"><?php echo $rekapan['kasir']; ?></td>
			<?php }

			$totalharga = $totalharga + $rekapan['price'];
			$totaljumlah = $totaljumlah + $rekapan['total'];
			$totalpokok = $totalpokok + $rekapan['hargapokok'];
			$totalmargin = $totalmargin + $rekapan['margin'];
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
	<td nowrap>Rp. <?= number_format($totalharga, 0, ',', '.') ?></td>
	<td></td>
	<td nowrap>Rp. <?= number_format($totaljumlah, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totaldiskon, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totalbayar, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totalpokok, 0, ',', '.') ?></td>
	<td nowrap>Rp. <?= number_format($totalmargin, 0, ',', '.') ?></td>
	<td colspan="3"></td>

</tr>