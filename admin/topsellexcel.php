<?php
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=topsell-" . $_GET['hari'] . ".xls");
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

if ($_SESSION['storeid'] == 0) {
    $storeid = '%';
} else {
    $storeid = $_SESSION['storeid'];
}
$storename = $_SESSION['confignamaresto'];

$totaljumlah = 0;
$rekapanquery = "SELECT b.itemid, menuItemName, b.price, sum(a.quantity) as totaljual, d.confignamaresto
FROM tbl_orderdetail a join tbl_menuitem b on a.itemID=b.itemID
join tbl_order c on a.orderID=c.orderID
join tbl_store d on b.storeid=d.storeid
where c.status not in('cancelled','expired' )
and b.storeid='{$storeid}' 
and order_date >='{$tgl}' and order_date <='{$tgl2}' 
group by b.itemid, menuItemName
order by sum(a.quantity) desc";

$html = "<body>
<table width='100%'>
    <tr>
        <td style='text-align:center;'>
            <h3>TOP SELL ITEM</h3><br>
            {$storename}
        </td>
        <br>
    </tr>
    </table>
<hr>
<table width='100%'>
    <tr>
        <td>Periode: {$tgl} - {$tgl2}</td> <td align='right'>Kasir: {$kasir}</td>
        </tr>
        </table>
<hr>
<table style='border: 1px solid black;' border='1' width='100%'>
<thead>
    <tr>
    <th>#</th>
    <th>Menu</th>
    <th>Price</th>
    <th>Total Terjual</th>
    <th>Resto</th>
    </tr>
</thead>
    <tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {

    $html .= "<tr>
        <td>" . $no++ . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['menuItemName'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['price'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['totaljual'] . "</td>";
    $html .= "<td nowrap align='left'>" . $rekapan['confignamaresto'] . "</td>";
    $html .= "</tr>";
    $totaljumlah = $totaljumlah + $rekapan['jumlah'];
}
$html .= "</tbody></table>
</body>";

echo $html;
