<?php
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

$totaljumlah = 0;
$rekapanquery = "SELECT a.idinventory, updatedate as pricedate,kategori,  namainventory, a.unitprice, unit,
(SELECT
round( sum( `tbl_invpriceupdate`.`unitprice` ) / count(*), 0 ) 
FROM
`tbl_invpriceupdate` where  tbl_invpriceupdate.idinventory=a.idinventory and tbl_invpriceupdate.updatedate>='{$tanggal}' and tbl_invpriceupdate.updatedate<='{$tanggal2}') as avgprice,
(SELECT jumlah FROM
`tbl_operasional` where  tbl_operasional.namatransaksi=a.namainventory and cast(tbl_operasional.tgltransaksi as date)=cast(a.updatedate as date) limit 1) as jmlbelanja,
(SELECT jmlitem FROM
`tbl_operasional` where  tbl_operasional.namatransaksi=a.namainventory and cast(tbl_operasional.tgltransaksi as date)=cast(a.updatedate as date) limit 1) as jmlitem,
idstore FROM `tbl_invpriceupdate` a join tbl_inventory b
on a.idinventory=b.idinventory
where active='1' and idstore='{$storeid}' and updatedate>='{$tanggal}' and updatedate<='{$tanggal2}' order by idinventory, updatedate asc";
$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>LAPORAN PERUBAHAN HARGA BAHAN BAKU</h3><br>
            <img src='../../image/{$img}' width='10%'>
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
                    <th>Tgl Update</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Belanja</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Average Price</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>";
    $html .= "<td>" . $no++ . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['pricedate'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['kategori'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['namainventory'] . "</td>";
    $html .= "<td nowrap align='left'>Rp. " . number_format($rekapan['jmlbelanja'], 0, ',', '.') . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['jmlitem'] . " " . $rekapan['unit'] .  "</td>";
    $html .= "<td nowrap align='left'>Rp. " . number_format($rekapan['unitprice'], 0, ',', '.') . " / " . $rekapan['unit'] . "</td>";
    $html .= "<td nowrap align='left'>Rp. " . number_format($rekapan['avgprice'], 0, ',', '.') . " / " . $rekapan['unit'] . "</td>";
    $html .= "</tr>";
}
$html .= "</table>
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
