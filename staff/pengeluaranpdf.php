<?php
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

$totaljumlah = 0;
$rekapanquery = "select * from tbl_operasional where storeid='{$storeid}' and tgltransaksi='{$tgl}'";
$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>LAPORAN PENGELUARAN HARIAN</h3><br>
            <img src='../../image/{$img}' width='10%'>
        </td>
        <br>
    </tr>
    </table>
<hr>
<table width='100%'>
    <tr>
        <td>Tanggal: {$tgl}</td> <td align='right'>Kasir: {$kasir}</td>
        </tr>
        </table>
<hr>
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

// echo $html;
//==============================================================
//==============================================================
//==============================================================

include("../include/mpdf/mpdf.php");

$mpdf = new mPDF();

$stylesheet = file_get_contents('../include/mpdf/mpdf.css');
$mpdf->SetFontSize(8);
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);

$mpdf->Output();

exit;

//==============================================================
//==============================================================
//==============================================================
