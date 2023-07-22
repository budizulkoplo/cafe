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
	select o.orderid,concat(o.orderid,'-',o.ordername,'<br>',s.storename, cast(OrderTime as time)) as ordername, m.menuname, od.itemid,case when od.pesan<>'' then concat(mi.menuitemname,'<br><i>note: ',od.pesan,'</i>') else mi.menuitemname end as menuitemname,od.quantity,o.status
	,(select img from tbl_store where storeid=mi.storeid) as imgfood
                    from tbl_order o
                    join tbl_orderdetail od
                    on o.orderid = od.orderid
                    left join tbl_menuitem mi
                    on od.itemid = mi.itemid
                    left join tbl_menu m
                    on mi.menuid = m.menuid
                    join tbl_store s
                    on o.storeid=s.storeid
                    where o.status 
                    in ( 'waiting','preparing','ready')
					and order_date>=curdate()-1
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
							<td align='left'>"; ?><img height="30" class="img=fluid" src="../image/<?php echo $orderRow['imgfood']; ?>" /> <?php echo " | " .
																																				$orderRow['menuitemname'] . "</td>
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

																																				echo "<td class='text-center' rowspan=" . $rowspan . ">";

																																				//options based on status of the order
																																				switch ($orderRow['status']) {
																																					case 'waiting':

																																						echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-primary' value = 'preparing'>Prepare</button>";
																																						echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-success' value = 'ready'>Ready</button>";

																																						break;

																																					case 'preparing':

																																						echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-success' value = 'ready'>Ready</button>";

																																						break;

																																					case 'ready':

																																						echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-warning' value = 'finish'>Clear</button>";

																																						break;
																																				}
																																				if ($orderRow['status'] <> 'ready') {
																																					echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-danger' value = 'cancelled'>Cancel</button></td>";
																																				}

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

																																$latestReadyQuery = "SELECT orderID FROM tbl_order WHERE status IN ( 'finish','ready') and order_date=curdate() ";

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