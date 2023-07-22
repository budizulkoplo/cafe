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
$rekapanquery = "select order_date as date, 
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

$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>TOP SELL ITEM</h3><br>
            <img src='../../image/{$img}' width='10%'>
        </td>
        <br>
    </tr>
    </table>
<hr>
<table width='100%'>
    <tr>
        <td>Periode: {$_GET['bulan']}</td> <td align='right'>Kasir: {$kasir}</td>
        </tr>
        </table>
<hr>
<table style='border: 1px solid black;' border='1' width='100%'>
<thead>
    <tr>
    <th>#</th>
    <th>Tanggal</th>
    <th>Omset</th>
    <th>Diskon Terjual</th>
    <th>Omset Bersih</th>
    <th>Guest</th>
    <th>APC</th>
    <th>Modal Terjual</th>
    <th>RP Margin</th>
    <th>% Margin</th>
    <th>Pengeluaran</th>
    <th>Cash</th>
    <th>Qris</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {
    $html .= "<tr>
        <td>" . $no++ . "</td>
        <td nowrap align='left'>" . $rekapan['date'] . "</td>
        <td nowrap align='right'>Rp. " . number_format($rekapan['omset'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['diskon'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['omsetbersih'], 0, ',', '.') . "</td>
        <td>" . $rekapan['guest'] . "</td>
        <td align='right' nowrap>Rp. " . number_format($rekapan['apc'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['modalterjual'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['rpmargin'], 0, ',', '.') . "</td>
        <td>" . $rekapan['persenmargin'] . " %</td>
        <td align='right'>Rp. " . number_format($rekapan['pengeluaran'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['cash'], 0, ',', '.') . "</td>
        <td align='right'>Rp. " . number_format($rekapan['qris'], 0, ',', '.') . "</td>
    </tr>";

    $totalomset += $rekapan['omset'];
    $totaldiskon += $rekapan['diskon'];
    $totalbersih += $rekapan['omsetbersih'];
    $totalcash += $rekapan['cash'];
    $totalqris += $rekapan['qris'];
    $totalguest += $rekapan['guest'];
    $totalmodalterjual += $rekapan['modalterjual'];
    $totalmargin += $rekapan['rpmargin'];
    $totalpengeluaran += $rekapan['pengeluaran'];
}
$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td align='center' colspan='2'>TOTAL</td>
<td nowrap align='right'>Rp. " . number_format($totalomset, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totaldiskon, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totalbersih, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totalguest, 0, ',', '.') . "</td>
<td></td>
<td nowrap align='right'>Rp. " . number_format($totalmodalterjual, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totalmargin, 0, ',', '.') . "</td>
<td></td>
<td nowrap align='right'>Rp. " . number_format($totalpengeluaran, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totalcash, 0, ',', '.') . "</td>
<td nowrap align='right'>Rp. " . number_format($totalqris, 0, ',', '.') . "</td></tr></tbody></table>
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
