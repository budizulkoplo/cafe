<body onload=window.print()>
    <?php
    session_start();
    require("../conf/dbconnection.php");
    ?>
    <script>
        function cetak() {
            window.print();
        }
    </script>
    <style>
        @media print {
            #print {
                display: none;
            }

            body {
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                font-size: 3px;
            }

            table {
                width: 100%;
            }
        }

        hr.dashed {
            border-top: 1px dashed black;
        }
    </style>

    <div id="print">
        <button type="button" class="btn btn-default" onclick="cetak()">Print</button>
        <a href="order.php" id="back" class="btn btn-default">Kembali Penjualan</a>
    </div>
    <center>
        <h6>--</h6>
    </center>
    <?php

    $orderid = $_GET['orderid'];

    $order = "SELECT a.orderid, order_date,
ordername,
case when pesan<>'' then concat(menuItemName,'<br><i>note: ',pesan,'</i>') else menuItemName end as namamenu,
price,
quantity,
(quantity*price) as totalharga,
total,
diskon,
jmlbayar,
uangditerima,
kembalian,
b.itemID,
jmlorang,
opsipesanan,
opsibayar,
note,
DATE_FORMAT(ordertime, '%H:%i:%s') as ortime
FROM `tbl_order` a join tbl_orderdetail b
on a.orderID=b.orderid
join tbl_menuitem c on b.itemID=c.itemID
where a.orderid='{$orderid}'";

    $result = $sqlconnection->query($order);
    $row = mysqli_fetch_assoc($result);
    ?>

    <table width="100%">
        <thead>
            <tr>
                <td colspan="6" align="left" style="text-align: center;">
                    <img src="../../image/<?php echo $_SESSION['img']; ?>" width="35%">
                    <!-- <h4><?php echo $_SESSION['confignamaresto']; ?></h4> -->
                    <br><?php echo $_SESSION['configalamat']; ?>
                    <hr class="dashed">
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align:left;" nowrap>No Nota : <?= $row['orderid'] ?><br />
                    Pelanggan: <?= $row['ordername'] ?><br>
                </th>
            </tr>
            <tr>
                <td colspan="6" style="text-align:left;" nowrap>
                    Ordertime : <?= date('d-m-Y', strtotime($row['order_date'])) ?> <?= $row['ortime'] ?>
            </tr>
            <tr>
                <td colspan='6'><i>Kasir:<?php echo $_SESSION['username']; ?></i></td>
            </tr>
            <tr>
                <td colspan='6'>
                    <hr class="dashed">
                </td>
            </tr>
            <tr style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">
                <th width="55%" style="text-align: center;" colspan='3'>Menu</th>
                <th width="12%" style="text-align: right;"></th>
                <th width="7%" style="text-align: center;">QTY</th>
                <th width="12%" style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = array();
            if ($result = $sqlconnection->query($order)) {
                if ($result->num_rows > 0) {
                    while ($orderRow = $result->fetch_array(MYSQLI_ASSOC)) {
                        if (empty($segment)) {
                            $segment = 1;
                        } else {
                            $segment++;
                        }
            ?>
                        <tr align="center" style="border-bottom: 1px dotted #000000;">
                            <td colspan='4' align='left'><span style="font-size:16pt;"><?= $orderRow['namamenu'] ?></span><br>
                                @ <?= number_format($orderRow['price'], 0, '', '.') ?></td>
                            <td valign='bottom'><?= $orderRow['quantity'] ?></td>
                            <td valign='bottom'><?= number_format($orderRow['totalharga'], 0, '', '.') ?></td>
                        </tr>

            <?php
                    }
                } else {
                    //no data retrieve
                    echo "null";
                }
            }
            ?>

            <?php if ($row['diskon'] > 0) { ?>
                <tr>
                    <td colspan='6'>
                        <hr class="dashed">
                    </td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Total Belanja = Rp. </td>
                    <td align="right"><?= number_format($row['total'], 0, '', '.') ?></td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Potongan = Rp. </td>
                    <td align="right" style='border-bottom: 1px solid #000000;'><?= number_format($row['diskon'], 0, '', '.') ?></td>
                </tr>
            <?php } else { ?>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Total Belanja = Rp. </td>
                    <td align="right" style='border-bottom: 1px solid #000000;'><?= number_format($row['total'], 0, '', '.') ?></td>
                </tr>
            <?php }
            ?>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">
                    <span style="font-size:14pt;">Jumlah Bayar = Rp.</span>
                </td>
                <td align="right">
                    <span style="font-size:14pt;"><?= number_format($row['jmlbayar'], 0, '', '.') ?></span>
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">Uang Diterima = Rp. </td>
                <td align="right"><?= number_format($row['uangditerima'], 0, '', '.') ?></td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">Kembalian = Rp. </td>
                <td align="right"><?= number_format($row['kembalian'], 0, '', '.') ?></td>
            </tr>

            <?php if ($row['note'] <> '') { ?>
                <tr>
                    <td colspan='6'>
                        <hr class="dashed">
                    </td>
                </tr>
                <tr>
                    <td colspan='6'>
                        <i><strong>note: <?= $row['note'] ?></strong></i>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan='6'>
                    <hr class="dashed">
                </td>
            </tr>

            <tr>
                <td colspan="6" style="text-align: center; padding-top:10px;"><?= $_SESSION['catatan']  ?></td>
            </tr>

        </tbody>
    </table>

    <!-- halaman kedua -->
    <hr class="dashed">
    <table>
        <tr>
            <td height='30'></td>
        </tr>
    </table>
    <hr class="dashed">
    <table width="100%">
        <thead>
            <tr>
                <td colspan="6" align="left" style="text-align: center;">
                    <img src="../../image/<?php echo $_SESSION['img']; ?>" width="35%">
                    <!-- <h4><?php echo $_SESSION['confignamaresto']; ?></h4> -->
                    <br><?php echo $_SESSION['configalamat']; ?>
                    <hr class="dashed">
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align:left;" nowrap>No Nota : <?= $row['orderid'] ?><br />
                    Pelanggan: <?= $row['ordername'] ?><br>
                </th>
            </tr>
            <tr>
                <td colspan="6" style="text-align:left;" nowrap>
                    Ordertime : <?= date('d-m-Y', strtotime($row['order_date'])) ?> <?= $row['ortime'] ?>
            </tr>
            <tr>
                <td colspan='6'><i>Kasir:<?php echo $_SESSION['username']; ?></i></td>
            </tr>
            <tr>
                <td colspan='6'>
                    <hr class="dashed">
                </td>
            </tr>
            <tr style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">
                <th width="55%" style="text-align: center;" colspan='3'>Menu</th>
                <th width="12%" style="text-align: right;"></th>
                <th width="7%" style="text-align: center;">QTY</th>
                <th width="12%" style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = array();
            if ($result = $sqlconnection->query($order)) {
                if ($result->num_rows > 0) {
                    while ($orderRow = $result->fetch_array(MYSQLI_ASSOC)) {
                        if (empty($segment)) {
                            $segment = 1;
                        } else {
                            $segment++;
                        }
            ?>
                        <tr align="center" style="border-bottom: 1px dotted #000000;">
                            <td colspan='4' align='left'><span style="font-size:16pt;"><?= $orderRow['namamenu'] ?></span><br>
                                @ <?= number_format($orderRow['price'], 0, '', '.') ?></td>
                            <td valign='bottom'><?= $orderRow['quantity'] ?></td>
                            <td valign='bottom'><?= number_format($orderRow['totalharga'], 0, '', '.') ?></td>
                        </tr>

            <?php
                    }
                } else {
                    //no data retrieve
                    echo "null";
                }
            }
            ?>

            <?php if ($row['diskon'] > 0) { ?>
                <tr>
                    <td colspan='6'>
                        <hr class="dashed">
                    </td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Total Belanja = Rp. </td>
                    <td align="right"><?= number_format($row['total'], 0, '', '.') ?></td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Potongan = Rp. </td>
                    <td align="right" style='border-bottom: 1px solid #000000;'><?= number_format($row['diskon'], 0, '', '.') ?></td>
                </tr>
            <?php } else { ?>
                <tr style="font-weight: bold;">
                    <td colspan="5" align="right">Total Belanja = Rp. </td>
                    <td align="right" style='border-bottom: 1px solid #000000;'><?= number_format($row['total'], 0, '', '.') ?></td>
                </tr>
            <?php }
            ?>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">
                    <span style="font-size:14pt;">Jumlah Bayar = Rp.</span>
                </td>
                <td align="right">
                    <span style="font-size:14pt;"><?= number_format($row['jmlbayar'], 0, '', '.') ?></span>
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">Uang Diterima = Rp. </td>
                <td align="right"><?= number_format($row['uangditerima'], 0, '', '.') ?></td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="5" align="right">Kembalian = Rp. </td>
                <td align="right"><?= number_format($row['kembalian'], 0, '', '.') ?></td>
            </tr>

            <?php if ($row['note'] <> '') { ?>
                <tr>
                    <td colspan='6'>
                        <hr class="dashed">
                    </td>
                </tr>
                <tr>
                    <td colspan='6'>
                        <i><strong>note: <?= $row['note'] ?></strong></i>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan='6'>
                    <hr class="dashed">
                </td>
            </tr>

            <tr>
                <td colspan="6" style="text-align: center; padding-top:10px;"><?= $_SESSION['catatan']  ?></td>
            </tr>

        </tbody>
    </table>

    <script>
        window.onmousemove = function() {
            window.close();
        }
    </script>