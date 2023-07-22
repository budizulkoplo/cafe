<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");



if (isset($_POST['oke'])) {

	if (isset($_POST['itemID']) && isset($_POST['itemqty'])) {

		$arrItemID = $_POST['itemID'];
		$arrItemQty = $_POST['itemqty'];
		$pemesan = $_POST['pemesan'];
		$jmlorang = $_POST['jmlorang'];
		$opsipesan = $_POST['opsipesan'];
		$opsibayar = $_POST['opsibayar'];
		$diskon = $_POST['diskon'];
		$total = $_POST['total'];
		$jmlbayar = ($_POST['total'] - $_POST['diskon']);
		$uangditerima = ($_POST['bayar']);
		$kembalian = ($_POST['kembalian']);
		$kasir = ($_SESSION['username']);
		$note = ($_POST['note']);

		//check pair of the array have same element number
		if (count($arrItemID) == count($arrItemQty)) {
			$arrlength = count($arrItemID);

			//add new id
			$currentOrderID = getLastID("orderID", "tbl_order") + 1;

            //cek duplikasi
            $cek = "SELECT * FROM tbl_order WHERE ordername = '$pemesan' and total='$total' and diskon='$diskon' and jmlbayar='$jmlbayar' and uangditerima='$uangditerima' and kembalian='$kembalian'";

$resultcek=$sqlconnection->query($cek);

if (mysqli_num_rows($resultcek)==0) {
			insertOrderQuery($currentOrderID, $pemesan, $jmlorang, $opsipesan, $opsibayar, $diskon, $jmlbayar, $total, $uangditerima, $kembalian, $kasir, $note);

			for ($i = 0; $i < $arrlength; $i++) {
				insertOrderDetailQuery($currentOrderID, $arrItemID[$i], $arrItemQty[$i]);
			}
			// updateTotal($currentOrderID);
			// header("location:struk.php?orderid=$currentOrderID");
			//masukan data order sebagai konsumsi material ready
			$consumption = "insert into tbl_consumption (orderid,itemid,itemqty,menuitemname,idinventory,inventoryqty, status) SELECT a.orderID, a.itemID, a.quantity, b.menuItemName, idinventory,qty,'waiting' FROM tbl_orderdetail a
			join tbl_menuitem b on a.itemID=b.itemID
			join tbl_detailinventory c on b.itemID=c.idmenu
			where orderID = {$currentOrderID};";

			$sqlconnection->query($consumption);
        }

?>
			<script>
				// Redirect ke halaman struk.php
				// window.location.href = "struk.php?orderid=" + <?php echo $currentOrderID; ?>;
				window.open("struk.php?orderid=" + <?php echo $currentOrderID; ?>);
                // window.open("struk.php?orderid=" + document.getElementById('orderid').value);
				
				// Membuat dokumen dinamis
			</script>

<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
  <button style="font-size: 24px; padding: 16px 32px; background-color: #4CAF50; color: white;">
    <a href="order.php" style="color: white; text-decoration: none;">
        Buat Order Baru
    </a>
  </button>
</div>


<?php
		} else {
			echo "xD";
		}
	}
}

function insertOrderDetailQuery($orderID, $itemID, $quantity)
{
	global $sqlconnection;
	$addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity) VALUES ('{$orderID}', '{$itemID}' ,{$quantity})";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		// echo "inserted.";
	} else {
		//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}

function insertOrderQuery($orderID, $pemesan, $jmlorang, $opsipesan, $opsibayar, $diskon, $jmlbayar, $total, $uangditerima, $kembalian, $kasir, $note)
{
	global $sqlconnection;
	$storeid = $_SESSION['storeid'];
	if (!$jmlorang) {
		$jmlorang = 1;
	}
	if (!$diskon) {
		$diskon = 0;
	}
	if (!$jmlbayar) {
		$jmlbayar = 0;
	}


	$addOrderQuery = "INSERT INTO tbl_order (orderID ,status, total, order_date,ordername,storeid,jmlorang,opsipesanan,opsibayar,diskon,jmlbayar,uangditerima,kembalian, kasir, note) VALUES ('{$orderID}' ,'waiting' , $total, CURDATE() , '{$pemesan}' , $storeid, $jmlorang, '{$opsipesan}', '{$opsibayar}', $diskon, $jmlbayar,$uangditerima,$kembalian, '{$kasir}','{$note}')";

	if ($sqlconnection->query($addOrderQuery) === TRUE) {
		// echo "inserted.";
	} else {
		//handle
		echo "someting wong";
		echo $sqlconnection->error;
	}
}
