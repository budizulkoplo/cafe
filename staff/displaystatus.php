
<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
	header("Location: login.php");

//display none when open /displayorder.php
if (empty($_GET['cmd']))
	die();

//display current order list for kitchen management
if ($_GET['cmd'] == 'currentorder') {

	$displayOrderQuery =  "
	select o.orderid,concat(o.orderid,'-',o.ordername,'<br>',s.storename) as ordername, m.menuname, od.itemid,mi.menuitemname,od.quantity,
case when o.status='ready' then '<p style=color:#46f540;><strong>ready</strong></p>' else o.status end as status 
                    from tbl_order o
                    left join tbl_orderdetail od
                    on o.orderid = od.orderid
                    left join tbl_menuitem mi
                    on od.itemid = mi.itemid
                    left join tbl_menu m
                    on mi.menuid = m.menuid
                    join tbl_store s
                    on o.storeid=s.storeid
                    where 
                    order_date=curdate()
                    order by o.orderid asc
				";

	if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

		$currentspan = 0;

		//if no order
		if ($orderResult->num_rows == 0) {

			echo "<tr><td class='text-center' colspan='7' >Tidak ada order untuk saat ini </td></tr>";
		} else {
			while ($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {

				$rowspan = getCountID($orderRow["orderid"], "orderid", "tbl_orderdetail");

				if ($currentspan == 0)
					$currentspan = $rowspan;

				echo "<tr>";

				if ($currentspan == $rowspan) {
					echo "<td rowspan=" . $rowspan . "># " . $orderRow['ordername'] . "</td>";
				}

				echo "
							<td>" . $orderRow['menuname'] . "</td>
							<td>" . $orderRow['menuitemname'] . "</td>
							<td class='text-center'>" . $orderRow['quantity'] . "</td>
						";

				if ($currentspan == $rowspan) {

					$color = "badge badge-warning";
					switch ($orderRow['status']) {
						case 'waiting':
							$color = "badge badge-warning";
							break;

						case 'preparing':
							$color = "badge badge-primary";
							break;

						case 'ready':
							$color = "badge badge-success";
							break;
					}

					echo "<td class='text-center' rowspan=" . $rowspan . "><span class='{$color}'>" . $orderRow['status'] . "</span></td>";



					//options based on status of the order

					echo "</td>";
				}

				echo "</tr>";

				$currentspan--;
			}
		}
	}
}

//display current ready order list in staff index
if ($_GET['cmd'] == 'currentready') {

	$latestReadyQuery = "SELECT orderID FROM tbl_order WHERE status IN ( 'finish','ready') ";

	if ($result = $sqlconnection->query($latestReadyQuery)) {

		if ($result->num_rows == 0) {
			echo "<tr><td class='text-center'>Tidak ada order yang status ready. </td></tr>";
		}

		while ($latestOrder = $result->fetch_array(MYSQLI_ASSOC)) {
			echo "<tr><td><i class='fas fa-bell text-danger'></i><b> Order #" . $latestOrder['orderID'] . "</b> is ready.<a href='editstatus.php?orderID=" . $latestOrder['orderID'] . "'><i class='fas fa-check float-right'></i></a></td></tr>";
		}
	}
}

?>