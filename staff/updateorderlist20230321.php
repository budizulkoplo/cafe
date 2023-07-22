
<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");


$orderid = $sqlconnection->real_escape_string($_GET['orderid']);

$displayOrderQuery =  "select  a.orderID, ordername, menuItemName as menuname, b.itemID, b.quantity, c.price, (c.price*quantity) as total  from tbl_order a join tbl_orderdetail b
on a.orderID=b.orderID
join tbl_menuitem c 
on b.itemID=c.itemID
where a.orderID = " . $orderid;

if ($orderResult = $sqlconnection->query($displayOrderQuery)) {
	$currentspan = 0;
	$total = 0;
	//if no order
	if ($menuItemResult = $sqlconnection->query($displayOrderQuery)) {
		if ($menuItemResult->num_rows > 0) {
			while ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {
				echo "
					<tr>
						<input type = 'hidden' name = 'itemID[]' value ='" . $menuItemRow['orderID'] . "'/>
						<td>" . $menuItemRow['menuname'] . "</td>
						<td>Rp. " . number_format($menuItemRow['price'], 0, '', '.') . "</td>
						<td><input type='text' name='qty[]' id='qty[]' onchange='updateqty(\"" . $menuItemRow['orderID'] . "\",\"" . $menuItemRow['itemID'] . "\", this.value)' value='" . $menuItemRow['quantity'] . "'/></td>
						<td>Rp. " . number_format($menuItemRow['total'], 0, '', '.') . "</td>
						<td><button class='btn btn-danger bg-danger deleteBtn' type='button' onclick='deleteRow(\"" . $menuItemRow['orderID'] . "\",\"" . $menuItemRow['itemID'] . "\")'>x</button></td>

					";
				$total = $total + $menuItemRow['total'];
			}
		} else {
			//no data retrieve
			echo "Tidak ada order dengan ID  $orderid";
		}

		echo "</tr>
		</tr>
						<tr class='bg-light text-dark'>
						<th colspan='3'>TOTAL</th><th colspan='2'><input type = 'hidden' name = 'total' id='total' value ='" . $total . "'/>
						Rp. " . number_format($total, 0, '', '.') . "</th>
						</tr>
						<tr class='bg-light text-dark'>
						<th colspan='3'>BAYAR</th><th colspan='2'><input type='text' name='bayar' id='bayar'></th>
						</tr>";

		$currentspan--;
	}
}


?>