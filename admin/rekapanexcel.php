<?php
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Rekapan-" . $_GET['tgl'] . ".xls");
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
if (empty($_GET['storeid'])) {
    $storeid = "%";
} else {
    $storeid = $sqlconnection->real_escape_string($_GET['storeid']);
}
$kasirstoreid = $_SESSION['storeid'];
$storename = $_SESSION['confignamaresto'];
$totalharga = 0;
$totaljumlah = 0;
$totaldiskon = 0;
$totalbayar = 0;
$rekapanquery = "select 
order_date, a.orderid,ordername, jmlorang, menuItemName as namamenu,(select storename from tbl_store where storeid=d.storeid) as restoitem,
 price, quantity, (price*quantity) as total, diskon, jmlbayar, opsipesanan, opsibayar, `desc` as storename, img,
 (select count(*) from tbl_orderdetail where itemID in((select itemID from tbl_menuitem where storeid like '{$storeid}'  and itemID in(
    SELECT DISTINCT(itemID) FROM tbl_orderdetail WHERE tbl_orderdetail.orderID = c.orderID ))
	) and tbl_orderdetail.orderID=a.orderID)	as rowspan  from tbl_order a join tbl_store b
on a.storeid=b.storeid join tbl_orderdetail c on a.orderID=c.orderID
join tbl_menuitem d on c.itemID=d.itemID
where  cast(order_date as date)='{$tgl}' and opsipesanan like '{$kategori}' and opsibayar like '{$bayar}'and d.storeid like '{$storeid}' and status in('completed','ready','finish')
and a.storeid='{$storeid}' 
order by a.orderid asc";
$html = "<body>
<table>
    <tr>
        <td style='text-align:center;' colspan='11'>
            <h3>REKAP TRANSAKSI HARIAN</h3>
            {$storename}
        </td>
    </tr>
    <tr>
        <td colspan='6'>Tanggal: {$tgl}</td> <td align='right' colspan='5'>Kasir: {$kasir}</td>
    </tr>
</table>

<table style='border: 1px solid black;' border='1'>
<thead>
    <tr>
    <th>#</th>
    <th>Pemesan</th>
    <th>Jml Orang</th>
    <th>Menu</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Jumlah</th>
    <th>Diskon</th>
    <th>Total</th>
    <th>Opsi Pesan</th>
    <th>Opsi Bayar</th>
    </tr></thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>
        <td>" . $no++ . "</td>";
    if ($orderid <> $rekapan['orderid']) {
        $html .= "<td rowspan='" . $rekapan['rowspan'] . "'><strong>" . $rekapan['orderid'] . " - " . $rekapan['ordername'] . "</strong></td>
                  <td rowspan='" . $rekapan['rowspan'] . "'>" . $rekapan['jmlorang'] . "</td>";
    }
    $html .= "<td nowrap align='left'>" . $rekapan['namamenu'] . " | " . $rekapan['restoitem'] . "</td>";
    if ($orderid <> $rekapan['orderid']) {
        $html .= "<td align='right'>Rp. " . number_format($rekapan['price'], 0, ',', '.') . "</td>
                  <td>" . $rekapan['quantity'] . "</td>
                  <td align='right'>Rp. " . number_format($rekapan['total'], 0, ',', '.') . "</td>
                  <td rowspan='" . $rekapan['rowspan'] . "' >Rp. " . number_format($rekapan['diskon'], 0, ',', '.') . "</td>
                  <td rowspan='" . $rekapan['rowspan'] . "' >Rp. " . number_format($rekapan['jmlbayar'], 0, ',', '.') . "</td>
                  <td rowspan='" . $rekapan['rowspan'] . "' >" . $rekapan['opsipesanan'] . "</td>
                  <td rowspan='" . $rekapan['rowspan'] . "' >" . $rekapan['opsibayar'] . "</td>";
    } else {
        $html .= "<td align='right'>Rp. " . number_format($rekapan['price'], 0, ',', '.') . "</td>
                  <td>" . $rekapan['quantity'] . "</td>
                  <td align='right'>Rp. " . number_format($rekapan['total'], 0, ',', '.') . "</td>";
    }
    $totalharga = $totalharga + $rekapan['price'];
    $totaljumlah = $totaljumlah + $rekapan['total'];
    if ($orderid <> $rekapan['orderid']) {
        $totaldiskon = $totaldiskon + $rekapan['diskon'];
        $totalbayar = $totalbayar + $rekapan['jmlbayar'];
    }
    $html .= "</tr>";
    $orderid = $rekapan['orderid'];
}
$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td align='center' colspan='4'>TOTAL</td>
<td nowrap align='right'>Rp. " . number_format($totalharga, 0, ',', '.') . "</td>
<td></td>
<td nowrap align='right'>Rp. " . number_format($totaljumlah, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totaldiskon, 0, ',', '.') . "</td>
<td nowrap>Rp. " . number_format($totalbayar, 0, ',', '.') . "</td>
<td colspan='2'></td>
</tr></tbody></table>
</body>";

echo $html;
