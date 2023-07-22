<?php
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=pengeluaran-" . $_GET['tgl'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../functions.php");
$kasir = ($_SESSION['username']);
$img = $_SESSION['img'];
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
if ($_SESSION['storeid'] == 0) {
    $storeid = '%';
} else {
    $storeid = $_SESSION['storeid'];
}
$kasirstoreid = $_SESSION['storeid'];
$storename = $_SESSION['confignamaresto'];

$totaljumlah = 0;
$rekapanquery = "select * from tbl_operasional where storeid='{$storeid}' and tgltransaksi='{$tgl}'";
$html = "<body>
<table>
    <tr>
        <td style='text-align:center;' colspan='4'>
            <h3>REKAP TRANSAKSI HARIAN</h3>
            {$storename}
        </td>
    </tr>
    <tr>
        <td colspan='2'>Tanggal: {$tgl}</td> <td align='right' colspan='2'>Kasir: {$kasir}</td>
    </tr>
</table>

<table style='border: 1px solid black;' border='1' width='100%'>
<thead>
    <tr>
    <th>#</th>
    <th>Nama Pengeluaran</th>
    <th>Jumlah</th>
    <th>Keterangan</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>
        <td>" . $no++ . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['namatransaksi'] . "</td>";
    $html .= '<td align="right">Rp. ' . number_format($rekapan['jumlah'], 0, ',', '.') . '</td>';
    $html .= "<td nowrap align='left'>" . $rekapan['keterangan'] . "</td>";

    $html .= "</tr>";
    $totaljumlah = $totaljumlah + $rekapan['jumlah'];
}
$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td align='center' colspan='2'>TOTAL</td>
<td nowrap align='right'>Rp. " . number_format($totaljumlah, 0, ',', '.') . "</td>
<td></td>
</tr></tbody></table>
</body>";

echo $html;
