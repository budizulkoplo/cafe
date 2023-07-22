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
$rekapanquery = "select 'cashonhand' as Transaksi, (select sum(nominal) from cashonhand where idstore like '{$storeid}') as Nominal
union
select 'Penjualan Cash' as Transaksi,(select ifnull(sum(jmlbayar),0) from tbl_order where opsibayar='cash' and status in('completed','ready','finish') and cast(order_date as date)='{$tgl}' and storeid like '{$storeid}') as Nominal
union
select 'Penjualan QR', (select ifnull(sum(jmlbayar),0) from tbl_order where opsibayar='qr-code' and status in('completed','ready','finish') and cast(order_date as date)='{$tgl}' and storeid like '{$storeid}')
union
select 'Pengeluaran', (select sum(jumlah) from tbl_operasional where cast(tgltransaksi as date)='{$tgl}' and storeid like '{$storeid}') ";
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
        <th>Transaksi</th>
        <th>Nominal</th>
        </tr>
</thead>
<tbody>";
$result = $sqlconnection->query($rekapanquery);
$no = 1;
$orderid = 0;
$qr = 0;
while ($rekapan = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($rekapan['Transaksi'] == 'cashonhand') {
        $html .= '<tr align="left">
                        <td>' . $rsno . '</td>
                        <td>Cash on Hand</td>
                        <td align="right">Rp. ' . number_format($rekapan['Nominal'], 0, ',', '.') . '</td>
                    </tr>
                    <tr height="20px">
                        <td colspan="3"></td>
                    </tr>';
    } elseif ($rekapan['Transaksi'] != 'cashonhand') {
        $html .= '<tr align="left">
                        <td>' . ++$rsno . '</td>
                        <td>' . $rekapan['Transaksi'] . '</td>
                        <td align="right">Rp. ' . number_format($rekapan['Nominal'], 0, ',', '.') . '</td>
                    </tr>';
    }
    if ($rekapan['Transaksi'] != 'Pengeluaran' && $rekapan['Transaksi'] != 'cashonhand') {
        $pemasukan += $rekapan['Nominal'];
    }
    if ($rekapan['Transaksi'] == 'Pengeluaran') {
        $pengeluaran += $rekapan['Nominal'];
    }
    if ($rekapan['Transaksi'] == 'Penjualan QR') {
        $qr = $qr + $rekapan['Nominal'];
    }
    if ($rekapan['Transaksi'] == 'cashonhand') {
        $cashonhand += $rekapan['Nominal'];
    }
}


$html .= "<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
<td colspan='2' align='left'>SUB TOTAL</td>
    <td align='right'>Rp. " . number_format($pemasukan - $pengeluaran, 0, ',', '.') . "</td>
</tr>
<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
    <td colspan='2' align='left'>SALDO</td>
    <td align='right'>Rp. " . number_format($pemasukan + $cashonhand - $pengeluaran, 0, ',', '.') . "</td>
</tr>
<tr bgcolor='#F8F9FA' style='font-weight:bold;'>
    <td colspan='2' align='left'>SALDO CASH</td>
    <td align='right'>Rp. " . number_format(($pemasukan + $cashonhand - $pengeluaran) - $qr, 0, ',', '.') . "</td>
</tr>
</tbody></table>";

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
