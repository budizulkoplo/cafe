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

$konsumsiquery = "SELECT cast(opnamedate as date) as opnamedate, namaitem, oldstock, newstock, unit, opnamedate as opnametime, opnameuser, 
(newstock-oldstock) as selisih,  
((select ifnull(unitprice,0) from tbl_invpriceupdate where tbl_invpriceupdate.idinventory=tbl_opname.idinventory order by updatedate desc limit 1 )* 
ABS(newstock-oldstock)) AS nominal,
IF(newstock < oldstock, 'stock kurang', 'stock lebih') AS stockstatus
FROM `tbl_opname`
where  MONTH(opnamedate)='{$bulan}'
AND YEAR(opnamedate)='{$tahun}'
and storeid='{$storeid}'
	order by opnamedate asc";


if ($result = $sqlconnection->query($konsumsiquery)) {

	if ($result->num_rows == 0) {

		echo "<td colspan='4'>data opname belum tersedia.</td>";
	}

	$no = 1;

	while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) { ?>
		<tr class="text-center">
			<td><?php echo $no++; ?></td>
			<td><?php echo $rekapan['opnamedate']; ?></td>
			<td><?php echo $rekapan['namaitem']; ?></td>
			<td><?php echo $rekapan['oldstock'] . " " . $rekapan['unit']; ?></td>
			<td><?php echo $rekapan['newstock'] . " " . $rekapan['unit']; ?></td>
			<td><?php echo $rekapan['selisih'] . " " . $rekapan['unit']; ?></td>
			<td align="right">Rp. <?php echo number_format($rekapan['nominal'], 0, ',', '.'); ?></td>
			<td><?php echo $rekapan['stockstatus']; ?></td>
			<td><?php echo $rekapan['opnametime']; ?></td>
			<td><?php echo $rekapan['opnameuser']; ?></td>
		</tr>
<?php
	}
} else {

	echo $sqlconnection->error;

	echo "Something wrong.";
} ?>