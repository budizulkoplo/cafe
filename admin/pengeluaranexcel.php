<?php
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=pengeluaran-" . $_GET['tanggal'] . "-" . $_GET['tanggal2'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../functions.php");
$kasir = ($_SESSION['username']);
$img = $_SESSION['img'];
if (!isset($_GET['tanggal'])) {
    $tanggal = date('Y-m-d');
} else {
    $tanggal = $_GET['tanggal'];
}

if (!isset($_GET['tanggal2'])) {
    $tanggal2 = date('Y-m-d');
} else {
    $tanggal2 = $_GET['tanggal2'];
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
$kasirstoreid = $_SESSION['storeid'];
$storename = $_SESSION['confignamaresto'];

$totaljumlah = 0;
$rekapanquery = "select cast(tgltransaksi as date) tgltransaksi,concat(cast(tgltransaksi as date),kategori) as cator, kategori,namatransaksi, jumlah, kasir, keterangan, jmlitem, hargasatuan, satuan,
(select count(*) from tbl_operasional where 
 tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan1,
(select count(*) from tbl_operasional where tbl_operasional.kategori=TBO.kategori 
and tbl_operasional.tgltransaksi=TBO.tgltransaksi and tbl_operasional.storeid=TBO.storeid) rowspan2  from tbl_operasional TBO where storeid='{$storeid}' and tgltransaksi>='{$tanggal}' and tgltransaksi<='{$tanggal2}'
ORDER BY tgltransaksi, kategori asc";
$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>LAPORAN PENGELUARAN</h3><br>
            {$storename}
        </td>
        <br>
    </tr>
    </table>
<hr>
<table width='100%'>
    <tr>
        <td>Periode: {$tanggal} - {$tanggal2}</td> <td align='right'>Kasir: {$kasir}</td>
        </tr>
        </table>
<hr>
<table style='border: 1px solid black;' border='1' width='100%'>
<thead>
    <tr>
    <th>#</th>
    <th>Tgl Transaksi</th>
    <th>Kategori</th>
    <th>Nama Pengeluaran</th>
    <th>Jumlah</th>
    <th>Keterangan</th>
    <th>Jml Belanja</th>
    <th>Harga Satuan</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>
        <td>" . $no++ . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['tgltransaksi'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['kategori'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['namatransaksi'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['jumlah'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['keterangan'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['jmlitem'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['hargasatuan'] . "</td>";
    $html .= "</tr>";
    $totaljumlah = $totaljumlah + $rekapan['jumlah'];
}
$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td align='center' colspan='4'>TOTAL</td>
<td nowrap align='right'>Rp. " . number_format($totaljumlah, 0, ',', '.') . "</td>
<td colspan='3'></td>
</tr></tbody></table>
</body>";

echo $html;
