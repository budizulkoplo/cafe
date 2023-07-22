
<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");
if (isset($_POST['btnMenuID'])) {
	echo "<div class='row'>";
	echo "";
	$menuID = $sqlconnection->real_escape_string($_POST['btnMenuID']);

	$menuItemQuery = "SELECT itemID,menuItemName FROM tbl_menuitem WHERE itemID not in(select idmenu from tbl_inventory j join tbl_detailinventory i
	on j.idinventory=i.idinventory
	left join tbl_consumption a on a.idinventory=j.idinventory and `status`='waiting'
  where jml-(qty+(ifnull(itemqty,0)*ifnull(inventoryqty,0)))<0) and menuID = " . $menuID . " order by menuItemname asc";
	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			$i = 0;
			while ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {
				$i++;
				echo "<div id='btnmenu[{$i}]' class='menu col-4'><button class='btn btn-warning bg-dark text-white' style='width:100%;' onclick = 'setQty({$menuItemRow['itemID']})'><h6>{$menuItemRow['menuItemName']}</h6></button></div>";
			}
		} else {
			echo "<div class='menu col-12'>Tidak ada menu tersedia.</div>";
		}
	}
}
echo "</div>";

if (isset($_POST['btnMenuItemID']) && isset($_POST['qty'])) {

	$menuItemID = $sqlconnection->real_escape_string($_POST['btnMenuItemID']);
	$quantity = $sqlconnection->real_escape_string($_POST['qty']);

	$menuItemQuery = "SELECT mi.itemID,mi.menuItemName,mi.price,m.menuName FROM tbl_menuitem mi LEFT JOIN tbl_menu m ON mi.menuID = m.menuID WHERE itemID = " . $menuItemID;

	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			if ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {
				echo "
					<tr>
						<input type = 'hidden' name = 'itemID[]' value ='" . $menuItemRow['itemID'] . "'/>
						<input type = 'hidden' name = 'harga[]' value ='" . $menuItemRow['price'] . "'/>
						<input type = 'hidden' name = 'qty[]' value ='" . $quantity . "'/>
						<td>" . $menuItemRow['menuName'] . " : " . $menuItemRow['menuItemName'] . "</td>
						<td>Rp. " . number_format($menuItemRow['price'], 0, '', '.') . "</td>
						<td><input type = 'number' required='required' min='1' max='50' name = 'itemqty[]' id='itemqty' onchange='updateitem(this.value)' width='10px' class='form-control' value ='" . $quantity . "'/></td>
						<td>Rp. " . number_format((float)$menuItemRow['price'] * $quantity, 0, '', '.') . "</td>
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
