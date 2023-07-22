<?php
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=periode-" . $_GET['tgl'] . ".xls");
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
$rekapanquery = "select 
order_date, a.orderid,ordername, jmlorang, menuItemName as namamenu, price, quantity, (price*quantity) as total, opsipesanan, opsibayar, `desc` as storename, img,
( SELECT count(itemID) FROM tbl_orderdetail WHERE tbl_orderdetail.orderID = c.orderID and itemid in(select itemID from tbl_menuitem) ) rowspan,
(modal*quantity) as hargapokok, (modal*quantity) as hargapokok, ((price*quantity)-(modal*quantity)) as margin, diskon,jmlbayar, kasir
from tbl_order a join tbl_store b
on a.storeid=b.storeid join tbl_orderdetail c on a.orderID=c.orderID
join tbl_menuitem d on c.itemID=d.itemID
where  cast(order_date as date)>='{$tgl}' and cast(order_date as date)<='{$tgl2}' and opsipesanan like '{$kategori}' and opsibayar like '{$bayar}'
and a.storeid like '{$storeid}' and status in('completed','ready','finish')
order by a.orderid asc";

$html = "<body>
<table>
    <tr>
        <td style='text-align:center;' colspan='15'>
            <h3>REKAP TRANSAKSI HARIAN</h3>
            {$storename}
        </td>
    </tr>
    <tr>
    <td>Tanggal: {$tgl} - {$tgl2} </td> <td align='right'>Kasir: {$kasir}</td>
    </tr>
</table>

<table style='border: 1px solid black;' border='1'>
<thead>
    <tr>
        <th>Tanggal</th>
        <th>Pemesan</th>
        <th>Jml Orang</th>
        <th>Menu</th>
        <th>Resto</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Jumlah</th>
        <th>Diskon</th>
        <th>Total</th>
        <th>Pokok</th>
        <th>Margin</th>
        <th>Opsi Pesan</th>
        <th>Opsi Bayar</th>
        <th>Kasir</th>
    </tr></thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= '<tr class="text-center">
    <td nowrap align="left">' . $rekapan['order_date'] . '</td>';
    if ($orderid <> $rekapan['orderid']) {
        $html .= '<td rowspan="' . $rekapan['rowspan'] . '" class="centered"><strong>' . $rekapan['orderid'] . ' - ' . $rekapan['ordername'] . '</strong></td>
        <td rowspan="' . $rekapan['rowspan'] . '" class="centered">' . $rekapan['jmlorang'] . '</td>';
    }
    $html .= '<td nowrap align="left">' . $rekapan['namamenu'] . '</td>';
    if ($orderid <> $rekapan['orderid']) {
        $html .= '<td rowspan="' . $rekapan['rowspan'] . '" class="centered">' . $rekapan['storename'] . '</td>';
    }
    $html .= '<td align="right">Rp. ' . number_format($rekapan['price'], 0, ',', '.') . '</td>
    <td>' . $rekapan['quantity'] . '</td>
    <td align="right">Rp. ' . number_format($rekapan['total'], 0, ',', '.') . '</td>';
    if ($orderid <> $rekapan['orderid']) {
        $html .= '<td rowspan="' . $rekapan['rowspan'] . '" class="centered" align="right">Rp. ' . number_format($rekapan['diskon'], 0, ',', '.') . '</td>
        <td rowspan="' . $rekapan['rowspan'] . '" class="centered" align="right">Rp. ' . number_format($rekapan['jmlbayar'], 0, ',', '.') . '</td>';
    }
    $html .= '<td align="right">Rp. ' . number_format($rekapan['hargapokok'], 0, ',', '.') . '</td>
    <td align="right">Rp. ' . number_format($rekapan['margin'], 0, ',', '.') . '</td>';
    if ($orderid <> $rekapan['orderid']) {
        $html .= '<td rowspan="' . $rekapan['rowspan'] . '" class="centered">' . $rekapan['opsipesanan'] . '</td>
        <td rowspan="' . $rekapan['rowspan'] . '" class="centered">' . $rekapan['opsibayar'] . '</td>
        <td rowspan="' . $rekapan['rowspan'] . '" class="centered">' . $rekapan['kasir'] . '</td>';
    }
    $totalharga = $totalharga + $rekapan['price'];
    $totaljumlah = $totaljumlah + $rekapan['total'];
    $totalpokok = $totalpokok + $rekapan['hargapokok'];
    $totalmargin = $totalmargin + $rekapan['margin'];
    if ($orderid <> $rekapan['orderid']) {
        $totaldiskon = $totaldiskon + $rekapan['diskon'];
        $totalbayar = $totalbayar + $rekapan['jmlbayar'];
    }
    $html .= "</tr>";
    $orderid = $rekapan['orderid'];
}
$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td align='center' colspan='5'>TOTAL</td>
<td nowrap align='right'>Rp. " . number_format($totalharga, 0, ',', '.') . "</td>
<td></td>
<td nowrap align='right'>Rp. " . number_format($totaljumlah, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totaldiskon, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totalbayar, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totalpokok, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totalmargin, 0, ',', '.') . "</td>
<td colspan='3'></td>
</tr></tbody></table>
</body>";

echo $html;
