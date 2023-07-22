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
        <a href="inventory.php" id="back" class="btn btn-default">Kembali ke Inventory</a>
    </div>

    <?php

    $storeid = $_SESSION['storeid'];

    $order = "select idinventory, nama, jml, unit, idstore, `desc` as storename from tbl_inventory a join tbl_store b on a.idstore=b.storeid where active='1' and a.idstore='{$storeid}'";

    $result = $sqlconnection->query($order);
    ?>


    <table width="100%"  border='1'>
        <thead>
            <tr>
                <td>Nama</td>
                <td>Stock</td>
            </tr>
        </thead>
        <tbody>
            <?php 
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    ?>
                
            <tr>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['jml'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
   
    <script>
        window.onmousemove = function() {
            window.open("struk2.php?orderid=" + <?php echo $orderid; ?>, "print", "menubar=no");
            window.close();
        }
    </script>