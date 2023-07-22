<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");
if (isset($_POST['btnMenuID'])) {

	$menuID = $sqlconnection->real_escape_string($_POST['btnMenuID']);

	$menuItemQuery = "SELECT itemID,menuItemName FROM tbl_menuitem WHERE menuID = " . $menuID;

	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			$counter = 0;
			while ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {

				if ($counter >= 3) {
					echo "</tr>";
					$counter = 0;
				}

				if ($counter == 0) {
					echo "<tr>";
				}

				echo "<td class='p-3'><button class='btn btn-warning bg-dark text-white ' onclick = 'setQty({$menuItemRow['itemID']})'><i class='fas fa-hamburger'></i><br/> {$menuItemRow['menuItemName']}</button></td>";

				$counter++;
			}
		} else {
			echo "<tr><td>Tidak ada menu tersedia.</td></tr>";
		}
	}
}

if (isset($_POST['orderID'])) {

	$menuItemID = $sqlconnection->real_escape_string($_POST['orderID']);

	$menuItemQuery = "select  a.orderID, ordername, menuItemName as menuname, b.itemID, b.quantity, c.price, (c.price*quantity) as total  from tbl_order a join tbl_orderdetail b
	on a.orderID=b.orderID
	join tbl_menuitem c 
	on b.itemID=c.itemID
	where a.orderID = " . $menuItemID;
	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			if ($itemrow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {
				echo "<table width='100%'>
				<tr align='center'>
				<td>Nama</td>
				<td>Jml Orang</td>
				</tr>
				<tr>
				<td><input type = 'hidden' name = 'orderid' value ='" . $itemrow['orderID'] . "'/>
				<input type = 'text'  style='width: 100%;' name = 'ordername' value ='" . $itemrow['ordername'] . "'/></td>
				<td><input type = 'text' style='width: 100%;' name = 'jml' value ='' placeholder='jml orang'/></td>
				
  </tr>
  <tr align='center'>
  <td>Opsi<br>Pesanan</td>
	<td>Opsi Bayar</td>
	</tr>
	<tr>
	<td><select style='width: 100%;'>
  <option value='dine-in'>Dine-in</option>
  <option value='go-food'>Go Food</option>
  <option value='shopee-food'>Shopee Food</option>
  <option value='grab-food'>Grab Food</option>
  <option value='take-away'>Take Away</option></td>
  <td><select style='width: 100%;'>
  <option value='cash'>Cash</option>
  <option value='qr-code'>QR Code</option>
  </select></td></tr>

  </table>

";
			}
		}
	}
	echo "<table  class='table ' width='100%' cellspacing='0'>
                        <tr>
                            <th>Menu</th>
                            <th>Price</th>
                            <th width='8%'>Qty</th>
                            <th>Total</th>
							<th></th>
                        </tr>
                    ";
	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			while ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {

				echo "
					<tr>
						<input type = 'hidden' name = 'itemID[]' value ='" . $menuItemRow['orderID'] . "'/>
						<td>" . $menuItemRow['menuname'] . "</td>
						<td>" . $menuItemRow['price'] . "</td>
						<td>" . $menuItemRow['quantity'] . "</td>
						<td>" . $menuItemRow['total'] . "</td>
						<td><button class='btn btn-danger bg-danger deleteBtn' type='button' onclick='deleteRow()'>x</button></td>
						</tr>
					";
			}
		} else {
			//no data retrieve
			echo "null";
		}
	}
}
