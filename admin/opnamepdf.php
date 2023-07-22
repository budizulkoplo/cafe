<?php
include("../functions.php");
$kasir = ($_SESSION['username']);
$img = $_SESSION['img'];
if (empty($_GET['hari'])) {
    $hari = 30;
} else {
    $hari = $sqlconnection->real_escape_string($_GET['hari']);
}


if (empty($_GET['bulan'])) {
    $bulan = "04";
    $tahun = "2023";
} else {
    $bulan = $sqlconnection->real_escape_string(substr($_GET['bulan'], -2));
    $tahun = $sqlconnection->real_escape_string(substr($_GET['bulan'], 0, 4));
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
$rekapanquery = "SELECT cast(opnamedate as date) as opnamedate, namaitem, oldstock, newstock, unit, opnamedate as opnametime, opnameuser, 
(newstock-oldstock) as selisih,  
((select ifnull(unitprice,0) from tbl_invpriceupdate where tbl_invpriceupdate.idinventory=tbl_opname.idinventory order by updatedate desc limit 1 )* 
ABS(newstock-oldstock)) AS nominal,
IF(newstock < oldstock, 'stock kurang', 'stock lebih') AS stockstatus
FROM `tbl_opname`
where  MONTH(opnamedate)='{$bulan}'
AND YEAR(opnamedate)='{$tahun}'
and storeid='{$storeid}'
	order by opnamedate asc";

$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>STOCK OPNAME</h3><br>
            <img src='../../image/{$img}' width='10%'>
        </td>
        <br>
    </tr>
    </table>
<hr>
<table width='100%'>
    <tr>
        <td>Periode: {$_GET['bulan']}</td> <td align='right'>User: {$kasir}</td>
        </tr>
        </table>
<hr>
<table style='border: 1px solid black;' border='1' width='100%'>
<thead>
    <tr>
    <th>#</th>
    <th>Tanggal</th>
    <th>Nama Item</th>
    <th>Old Stock</th>
    <th>New Stock</th>
    <th>Selisih</th>
    <th>Nominal</th>
    <th>Ket.</th>
    <th>Opname Time</th>
    <th>Opname User</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>
    <td>" . $no++ . "</td>";
    $html .= "<td>" . $rekapan['opnamedate'] . "</td>";
    $html .= "<td>" . $rekapan['namaitem'] . "</td>";
    $html .= "<td>" . $rekapan['oldstock'] . " " . $rekapan['unit'] . "</td>";
    $html .= "<td>" . $rekapan['newstock'] . " " . $rekapan['unit'] . "</td>";
    $html .= "<td>" . $rekapan['selisih'] . " " . $rekapan['unit'] . "</td>";
    $html .= "<td align='right'>Rp. " . number_format($rekapan['nominal'], 0, ',', '.') . "</td>";
    $html .= "<td>" . $rekapan['stockstatus'] . "</td>";
    $html .= "<td>" . $rekapan['opnametime'] . "</td>";
    $html .= "<td>" . $rekapan['opnameuser'] . "</td>";
    $html .= "</tr>";
}
$html .= "</tbody></table>
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
