<?php
include("../functions.php");

if (empty($_GET['cmd']))
	die();
if ($_GET['cmd'] != 'display')
	die();

if (empty($_GET['bulan'])) {
	$bulan = "04";
	$tahun = "2023";
} else {
	$bulan = $sqlconnection->real_escape_string(substr($_GET['bulan'], -2));
	$tahun = $sqlconnection->real_escape_string(substr($_GET['bulan'], 0, 4));
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
$totalomset = 0;
$totaldiskon = 0;
$totalbersih = 0;
$totalcash = 0;
$totalqris = 0;
$totalguest = 0;
$totalmodalterjual = 0;
$totalmargin = 0;
$totalpengeluaran = 0;

$konsumsiquery = "select order_date as date, 
sum(total) as omset, 
sum(diskon) as diskon, 
sum(jmlbayar) as omsetbersih ,
count(*) as guest,
(sum(total)/count(*)) as apc,
(select sum(modal) from vw_modal where vw_modal.order_date=od.order_date and vw_modal.storeid=od.storeid) as modalterjual,
(sum(jmlbayar)-(select sum(modal) from vw_modal where vw_modal.order_date=od.order_date and vw_modal.storeid=od.storeid)) as rpmargin,
round((sum(jmlbayar)-(select sum(modal) from vw_modal where vw_modal.order_date=od.order_date and vw_modal.storeid=od.storeid))/sum(jmlbayar)*100) as persenmargin,
ifnull((select sum(jmlbayar) from tbl_order io where opsibayar='cash' and status in('completed','ready','finish') and storeid='{$storeid}' and io.order_date=od.order_date),0) as cash,
ifnull((select sum(jmlbayar) from tbl_order io where opsibayar='qr-code' and status in('completed','ready','finish') and storeid='{$storeid}' and io.order_date=od.order_date),0) as qris,
(select sum(jumlah) from tbl_operasional where cast(tgltransaksi as date)=cast(od.order_date as date) and tbl_operasional.storeid=od.storeid) as pengeluaran
from tbl_order od 
where  MONTH(order_date)='{$bulan}'
AND YEAR(order_date)='{$tahun}'
and storeid='{$storeid}'
and status in('completed','ready','finish')
	group by order_date
	order by order_date asc";

echo $konsumsiquery;
if ($result = $sqlconnection->query($konsumsiquery)) {

	if ($result->num_rows == 0) {

		echo "<td colspan='4'>data konsumsi belum tersedia.</td>";
	}

	$no = 1;

	while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) { ?>
		<tr class="text-center">
			<td><?php echo $no++; ?></td>
			<td nowrap><?php echo $rekapan['date']; ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['omset'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['diskon'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['omsetbersih'], 0, ',', '.'); ?></td>
			<td><?php echo $rekapan['guest']; ?></td>
			<td align="right" nowrap>Rp. <?php echo number_format($rekapan['apc'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['modalterjual'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['rpmargin'], 0, ',', '.'); ?></td>
			<td><?php echo $rekapan['persenmargin']; ?> %</td>
			<td align="right">Rp. <?php echo number_format($rekapan['pengeluaran'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['cash'], 0, ',', '.'); ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['qris'], 0, ',', '.'); ?></td>
		</tr>

<?php
		$totalomset = $totalomset + $rekapan['omset'];
		$totaldiskon = $totaldiskon + $rekapan['diskon'];
		$totalbersih = $totalbersih + $rekapan['omsetbersih'];
		$totalcash = $totalcash + $rekapan['cash'];
		$totalqris = $totalqris + $rekapan['qris'];
		$totalguest = $totalguest + $rekapan['guest'];
		$totalmodalterjual = $totalmodalterjual + $rekapan['modalterjual'];
		$totalmargin = $totalmargin + $rekapan['rpmargin'];
		$totalpengeluaran = $totalpengeluaran + $rekapan['pengeluaran'];
	}
} else {

	echo $sqlconnection->error;

	echo "Something wrong.";
} ?>
<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
	<td align='center' colspan='2'>TOTAL</td>
	<td nowrap align='right'>Rp. <?= number_format($totalomset, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totaldiskon, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totalbersih, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totalguest, 0, ',', '.') ?></td>
	<td></td>
	<td nowrap align='right'>Rp. <?= number_format($totalmodalterjual, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totalmargin, 0, ',', '.') ?></td>
	<td></td>
	<td nowrap align='right'>Rp. <?= number_format($totalpengeluaran, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totalcash, 0, ',', '.') ?></td>
	<td nowrap align='right'>Rp. <?= number_format($totalqris, 0, ',', '.') ?></td>

</tr>