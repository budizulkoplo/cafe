<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
	header("Location: login.php");

if ($_SESSION['user_level'] != "admin")
	header("Location: login.php");

if (empty($_GET['cmd']))
	die();
if ($_GET['cmd'] != 'display')
	die();

$displayOrderQuery =  "
select o.orderid,concat(o.orderid,'-',o.ordername,'<br>',s.storename) as ordername, m.menuname, od.itemid,mi.menuitemname,od.quantity,
o.status,(select img from tbl_store where storeid=mi.storeid) as imgfood
                    from tbl_order o
                    join tbl_orderdetail od
                    on o.orderid = od.orderid
                    left join tbl_menuitem mi
                    on od.itemid = mi.itemid
                    left join tbl_menu m
                    on mi.menuid = m.menuid
                    join tbl_store s
                    on o.storeid=s.storeid
                    where  order_date>=curdate()-1
                    order by o.orderid asc";

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

								<td class='text-center'>" . $orderRow['quantity'] . "</td>";

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

																																					echo "<td class='text-center' rowspan=" . $rowspan . ">" . $orderRow['status'] . "</td>";

																																					echo "<td class='text-center' rowspan=" . $rowspan . ">";

																																					//options based on status of the order
																																					switch ($orderRow['status']) {
																																						case 'ready':
																																							echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-danger' value = 'cancelled'>Cancel</button></td>";
																																							break;
																																						case 'Completed':
																																							echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-danger' value = 'cancelled'>Cancel</button></td>";
																																							break;
																																						case 'finish':
																																							echo "<button onclick='editStatus(this," . $orderRow['orderid'] . ")' class='btn btn-danger' value = 'cancelled'>Cancel</button></td>";
																																							break;
																																					}

																																					echo "</td>";
																																				}

																																				echo "</tr>";

																																				$currentspan--;
																																			}
																																		}
																																	}



																																				?>