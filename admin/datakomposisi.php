<?php

include("../functions.php");

//tambahan upload gambar

if (isset($_FILES['gambar'])) {
    // Tentukan ukuran maksimum lebar
    $max_width = 300;
    $file_name = $_FILES['gambar']['name'];
    $file_size = $_FILES['gambar']['size'];
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_type = $_FILES['gambar']['type'];

    // Pindahkan gambar yang diunggah ke direktori tujuan
    move_uploaded_file($file_tmp, "../image/menu/" . $file_name);

    // Resize gambar
    $resized_image = resizeImage("../image/menu/" . $file_name, 400);

    // Simpan gambar yang diresize ke direktori tujuan
    $resized_image_path = "../image/menu/rz_" . $file_name;
    imagejpeg($resized_image, $resized_image_path);

    // Hapus gambar sumber yang tidak diresize
    unlink("../image/menu/" . $file_name);

    // echo "Gambar berhasil diunggah dan diresize!";
}

function resizeImage($image_path, $max_width)
{
    list($width, $height, $type) = getimagesize($image_path);

    $new_width = $max_width;
    $new_height = ($height / $width) * $new_width;

    $new_image = imagecreatetruecolor($new_width, $new_height);
    $source_image = imagecreatefromjpeg($image_path);

    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    return $new_image;
}


//tambahan upload gambar

//Add new menu item

if (isset($_POST['namamenu'])) {
    if (!empty($_POST['namamenu'])) {
        $storeid = $sqlconnection->real_escape_string($_POST['storeid']);
        $namamenu = $sqlconnection->real_escape_string($_POST['namamenu']);
        $hargajual = $sqlconnection->real_escape_string($_POST['hargajual']);
        $menuid = $sqlconnection->real_escape_string($_POST['menuid']);
        if (isset($file_name)) {
            $menuimg = "rz_" . $file_name;
        } else {
            $menuimg = "menu.png";
        }


        $komposisi = $_POST['komposisi'];
        $qty = $_POST['qty'];

        $itemid = "SELECT MAX(itemid) as itemid FROM tbl_menuitem";
        $result = $sqlconnection->query($itemid);
        $row = mysqli_fetch_assoc($result);
        $id = $row['itemid'] + 1;

        for ($i = 0; $i < count($_POST['komposisi']); $i++) {
            $query = "INSERT INTO tbl_detailinventory (idmenu, idinventory, qty) VALUES ('" . $id . "', '" . $komposisi[$i] . "', '" . $qty[$i] . "')";
            echo $query;
            $sqlconnection->query($query);
        }

        if ($_POST['hargamodal'] <> "") {
            $hargamodal = $sqlconnection->real_escape_string($_POST['hargamodal']);
        } else {
            $carihargamodal = "SELECT sum(ifnull(unitprice,0)*qty) as total FROM `tbl_detailinventory` a join tbl_inventory b on a.idinventory=b.idinventory  where a.idmenu='{$id}'";
            $priceresult = $sqlconnection->query($carihargamodal);
            while ($row = $priceresult->fetch_array(MYSQLI_ASSOC)) {
                $hargamodal = $row["total"];
            }
            echo $carihargamodal;
        }

        $addItemQuery = "INSERT INTO tbl_menuitem (menuID ,menuItemName ,price, storeid, modal, menuimg) VALUES ({$menuid} ,'{$namamenu}' ,{$hargajual},{$storeid},{$hargamodal},'{$menuimg}')";
        // header("Location: menu.php");
        // echo $addItemQuery;
        $sqlconnection->query($addItemQuery);

        echo "Data berhasil disimpan.";
    }

    //No input handle

    else {
        echo "tidak boleh kosong";
    }
}
