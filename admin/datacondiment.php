<?php

include("../functions.php");

if (isset($_POST["ambil"])) {
	if ($_POST["ambil"] == 'komposisiawal') {
		$storeid = $_POST['storeid'];
		$str = "SELECT * FROM tbl_inventory WHERE idstore='$storeid' and active='1' and idinventory NOT in (";
		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$str .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$str .= "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {
			$str .= "''";
		}
		$str .= ") and idstore='$storeid'";

		if ($orderResult = $sqlconnection->query($str)) {
			$currentspan = 0;
			//if no order
			if ($orderResult->num_rows == 0) {
				echo "<tr><td class='text-center' colspan='7' >Tidak komposisi untuk saat ini </td></tr>";
			} else {
				while ($row = $orderResult->fetch_array(MYSQLI_ASSOC)) {
?>

					<div class="radio">

						<label>

							<input type="radio" name="komposisi" value="<?php echo $row['idinventory']; ?>">

							<?php echo $row["nama"]; ?>

						</label>

					</div>

				<?php

				}
			}
		}
	}



	if ($_POST["ambil"] == 'komposisiakhir') {
		$str = "SELECT * FROM tbl_inventory WHERE idinventory IN (";
		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$str .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$str .= "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {

			$str .= "''";
		}



		$str .= ")";



		if ($orderResult = $sqlconnection->query($str)) {



			$currentspan = 0;



			//if no order

			if ($orderResult->num_rows == 0) {



				echo "<tr><td class='text-center' colspan='7' >Tidak komposisi untuk saat ini </td></tr>";
			} else {

				while ($row = $orderResult->fetch_array(MYSQLI_ASSOC)) {

				?>

					<tr cellspacing='2' cellpadding='2'>

						<td><input type="radio" name="komposisiterpilih" value="<?php echo $row['idinventory']; ?>">

							<?php echo $row["nama"]; ?></td>

						<td>&nbsp<input type="text" name="qty" value=""></td>

						<td>&nbsp<?php echo $row["unit"]; ?></td>


					</tr>

				<?php

				}
			}
		}
	}


	if ($_POST["ambil"] == 'editkomposisi') {
		$storeid = $_POST['storeid'];
		$itemid = $_POST['menuid'];
		$str = "SELECT * FROM tbl_inventory WHERE idstore='$storeid' and active='1' and idinventory not in(select idinventory from tbl_detailinventory where idmenu='" . $itemid . "') and idinventory NOT in (";
		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$str .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$str .= "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {
			$str .= "''";
		}
		$str .= ") and idstore='$storeid'";

		if ($orderResult = $sqlconnection->query($str)) {
			$currentspan = 0;
			//if no order
			if ($orderResult->num_rows == 0) {
				echo "<tr><td class='text-center' colspan='7' >Tidak komposisi untuk saat ini </td></tr>";
			} else {
				while ($row = $orderResult->fetch_array(MYSQLI_ASSOC)) {
				?>

					<div class="radio">

						<label>

							<input type="radio" name="komposisi" value="<?php echo $row['idinventory']; ?>">

							<?php echo $row["nama"]; ?>

						</label>

					</div>

				<?php

				}
			}
		}
	}

	if ($_POST["ambil"] == 'editkomposisiakhir') {
		$itemid = $_POST['menuid'];
		$str = "SELECT *, (select qty from tbl_detailinventory where tbl_detailinventory.idinventory=inv.idinventory and tbl_detailinventory.idmenu='" . $itemid . "' limit 1) AS qty FROM tbl_inventory inv WHERE idinventory in (select idinventory from tbl_detailinventory where idmenu='" . $itemid . "')  or idinventory IN (";
		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$str .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$str .= "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {

			$str .= "''";
		}



		$str .= ")";
		echo $str;



		if ($orderResult = $sqlconnection->query($str)) {



			$currentspan = 0;



			//if no order

			if ($orderResult->num_rows == 0) {



				echo "<tr><td class='text-center' colspan='7' >Tidak komposisi untuk saat ini </td></tr>";
			} else {

				while ($row = $orderResult->fetch_array(MYSQLI_ASSOC)) {

				?>

					<tr cellspacing='2' cellpadding='2'>

						<td><input type="radio" name="komposisiterpilih" value="<?php echo $row['idinventory']; ?>">

							<?php echo $row["nama"]; ?></td>

						<td>&nbsp<input type="text" name="qty" value="<?php echo $row["qty"]; ?>"></td>

						<td>&nbsp<?php echo $row["unit"]; ?></td>

					</tr>

				<?php

				}
			}
		}
	}

	if ($_POST["ambil"] == 'resetkomposisiakhir') {
		$itemid = $_POST['menuid'];

		$reset = "delete from tbl_detailinventory where idmenu='" . $itemid . "'";

		$str = "SELECT *, (select qty from tbl_detailinventory where tbl_detailinventory.idinventory=inv.idinventory and tbl_detailinventory.idmenu='" . $itemid . "' limit 1) AS qty FROM tbl_inventory inv WHERE idinventory in (select idinventory from tbl_detailinventory where idmenu='" . $itemid . "')  or idinventory IN (";
		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$str .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$str .= "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {

			$str .= "''";
		}



		$str .= ")";
		echo $str;


		$sqlconnection->query($reset);
		if ($orderResult = $sqlconnection->query($str)) {



			$currentspan = 0;



			//if no order

			if ($orderResult->num_rows == 0) {



				echo "<tr><td class='text-center' colspan='7' >Tidak komposisi untuk saat ini </td></tr>";
			} else {

				while ($row = $orderResult->fetch_array(MYSQLI_ASSOC)) {

				?>

					<tr cellspacing='2' cellpadding='2'>

						<td><input type="radio" name="komposisiterpilih" value="<?php echo $row['idinventory']; ?>">

							<?php echo $row["nama"]; ?></td>

						<td>&nbsp<input type="text" name="qty" value="<?php echo $row["qty"]; ?>"></td>

						<td>&nbsp<?php echo $row["unit"]; ?></td>

					</tr>

<?php

				}
			}
		}
	}


	if ($_POST['ambil'] == "submitkomposisi") {



		$strkomposisi = "";

		$JSON = array();



		if (isset($_POST['komposisi'])) {

			for ($i = 0; $i < count($_POST['komposisi']); $i++) {

				if ($i > 0) {

					$strkomposisi .= ",'" . $_POST['komposisi'][$i] . "'";
				} else {

					$strkomposisi = "'" . $_POST['komposisi'][$i] . "'";
				}
			}
		} else {

			$strkomposisi .= "''";
		}



		$str1 = "SELECT A.id_penyakit, COUNT(*) FROM tbl_penyakit A INNER JOIN tbl_komposisi_penyakit B ON A.id_penyakit = B.id_penyakit WHERE A.aktif = 1 AND B.id_komposisi IN(" . $strkomposisi . ") GROUP BY A.id_penyakit HAVING COUNT(*) = '" . COUNT($_POST['komposisi']) . "'";

		$exec1 = mysql_query($str1);

		// echo  $str1;



		while ($row1 = mysql_fetch_assoc($exec1)) {



			$str2 = "SELECT count(*) jml FROM tbl_komposisi_penyakit WHERE id_penyakit = '" . $row1["id_penyakit"] . "'";

			$exec2 = mysql_query($str2);

			$row2 = mysql_fetch_assoc($exec2);

			// echo $str2;

			if ($row2["jml"] == count($_POST['komposisi'])) {

				// echo $count;

				$id_penyakit = $row1["id_penyakit"];

				break;
			}
		}



		if (!isset($id_penyakit)) {

			$JSON["error"] = 1;

			$JSON["msg"] = "Data penyakit dengan komposisi yang diinputkan tidak ditemukan";

			echo json_encode($JSON);
		} else {

			// echo $id_penyakit;

			$str3 = "SELECT * FROM tbl_penyakit WHERE aktif = '1' and  id_penyakit = '" . $id_penyakit . "'";

			$exec3 = mysql_query($str3);

			$row3 = mysql_fetch_assoc($exec3);



			$JSON = $row3;



			$JSON["error"] = 0;

			$JSON["msg"] = "Penyakit dengan komposisi-komposisi tersebut adalah : " . $row3["nama_penyakit"];



			echo json_encode($JSON);
		}
	}
}



if (isset($_POST["submit"])) {



	$str = "INSERT INTO tbl_konsultasi (idpasien, id_penyakit, tgl_konsultasi, user_input) VALUES ('" . $_POST['idpasien'] . "','" . $_POST['txtPenyakit'] . "',SYSDATE(),'" . $_SESSION["userid"] . "')";



	$exec = mysql_query($str);



	// $strid = "SELECT * FROM tbl_konsultasi WHERE nama_pasien = '".$_POST['txtNama']."' AND umur = '".$_POST['txtUmur']."' AND alamat = '".$_POST['txtAlamat']."' AND user_input = '".$_SESSION["userid"]."'";

	$strid = "SELECT * FROM tbl_konsultasi order by id desc limit 1";

	$execid = mysql_query($strid);

	$row = mysql_fetch_assoc($execid);



	echo $row['id'];

	echo $row['idpasien'];
}

?>