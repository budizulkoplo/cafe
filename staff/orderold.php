<?php
include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])))
  header("Location: login.php");

if ($_SESSION['user_level'] != "staff")
  header("Location: login.php");

if ($_SESSION['user_role'] != "waiters") {
  echo ("<script>window.alert('Available for waiters only!'); window.location.href='index.php';</script>");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<?php
include("header.php");
?>
<div class="container">
  <div class="row">
    <div class="col-12 col-md-12 p-3 p-md-3"></div>
    <div class="col-12 col-md-4 p-3 p-md-3 shadow rounded">
      <!-- Page Content -->
      <h1 class="text-center"><strong>Menu Order</strong></h1>
      <h3 class="text-center"><?php echo $_SESSION['storename']; ?></h3>
      <hr>
      <p class="text-center">Pilih menu dan bikin order lalu kirim ke dapur untuk di proses.</p>
      <table class="table text-center" width="100%" cellspacing="0">
        <tr>
          <?php
          $menuQuery = "SELECT * FROM tbl_menu";

          if ($menuResult = $sqlconnection->query($menuQuery)) {
            $counter = 0;
            while ($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) {
              if ($counter >= 3) {
                echo "</tr>";
                $counter = 0;
              }

              if ($counter == 0) {
                echo "<tr>";
              }
          ?>
              <td class="bg-dark"><button style="margin-bottom:4px;white-space: normal;" class="btn btn-warning" onclick="displayItem(<?php echo $menuRow['menuID'] ?>)"><?php echo $menuRow['menuName'] ?> <i class="fas fa-utensils"></i></button></td>
          <?php

              $counter++;
            }
          }
          ?>
        </tr>
      </table>
      <table id="tblItem" class="table table-bordered text-center bg-warning text-white" width="100%" cellspacing="0"></table>

      <div id="qtypanel" hidden="">
        Qty : <input id="qty" required="required" type="number" min="1" max="50" name="qty" value="1" />
        <button class="btn btn-dark" onclick="insertItem()">+ Order</button>
        <br><br>
      </div>

    </div>



    <div class="col-12 col-md-8 p-3 p-md-3 ">
      <div class="card-header text-center bg-dark text-white">
        List Order</div>
      <div class="card-body">
        <form action="insertorder.php" method="POST">

          <table width="100%">
            <tr align='center'>
              <td>
                <h6>Pemesan</6>
              </td>
              <td>
                <h6>Jml Orang</h6>
              </td>
            </tr>
            <tr>
              <td>
                <input type="text" required="required" class="form-control" name="pemesan" placeholder="nama pemesan ex: meja 1">
              </td>
              <td><input type='text' name="jmlorang" class="form-control" name='jml' value='' placeholder='jml orang' /></td>

            </tr>
            <tr align='center'>
              <td>
                <h6>Opsi<br>Pesanan</h6>
              </td>
              <td>
                <h6>Opsi Bayar</h6>
              </td>
            </tr>
            <tr>
              <td><select name="opsipesan" class="form-control">
                  <option value='dine-in'>Dine-in</option>
                  <option value='go-food'>Go Food</option>
                  <option value='shopee-food'>Shopee Food</option>
                  <option value='grab-food'>Grab Food</option>
                  <option value='take-away'>Take Away</option>
              </td>
              <td><select name="opsibayar" class="form-control">
                  <option value='cash'>Cash</option>
                  <option value='qr-code'>QR Code</option>
                </select></td>
            </tr>

          </table>
          <hr>
          <table id="tblOrderList" class="table " width="100%" cellspacing="0">
            <tr>
              <th>Menu</th>
              <th>Price</th>
              <th width="8%">Qty</th>
              <th>Total</th>
            </tr>
          </table>

          <table width="100%">
            <tr>
              <td>
                <h6>Jumlah</h6>
              </td>
              <td align="right" colspan='2'><input style="width: 100%; text-align: right; color:red;" type='text' id="total" class="form-control" name='total' readonly /></td>

            </tr>
            <tr>
              <td>
                <h6>Diskon</h6>
              </td>
              <td align="right"><input style="width: 100%; text-align: right;" type='text' id="diskon" class="form-control" name='diskon' />
              </td>
              <td align="right"><input style="width: 100%; text-align: right;" type='text' id="persen" class="form-control" name='persen' placeholder="%" /></td>
            </tr>
            <tr>
              <td>
                <h6>Total (Total harus dibayar total harga - diskon)</h6>
              </td>
              <td align="right" colspan='2'><input style="width: 100%; text-align: right; color:green;" onchange=calculate() type='text' id="totalbayar" class="form-control" name='totalbayar' readonly /></td>
            </tr>
            <tr>
              <td>
                <h6>Bayar (Jumlah uang dibayarkan)</h6>
              </td>
              <td align="right" colspan='2'><input style="width: 100%; text-align: right; color:green;" type='text' id="bayar" class="form-control" name='bayar' /></td>
            </tr>
            <tr id="kembalianRow">
              <td>
                <h6>Kembali</h6>
              </td>
              <td align="right" colspan='2'><input style="width: 100%; text-align: right; color:blue;" onchange=susuk() type='text' id="kembalian" class="form-control" name='kembalian' readonly />
                <input style="width: 100%; text-align: right; color:blue;" type='hidden' id="oke" class="form-control" name='oke' value='bayar' />
              </td>
            </tr>
          </table>
          <input class="btn btn-dark btn-lg col-12" type="submit" name="sentorder" value="BAYAR">

        </form>
      </div>
    </div>
  </div>


  <div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white text-center">
    Copyright © Zul App Developers
  </div>


</div>
<!-- /.content-wrapper -->



<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin akan off?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Pilih off untuk keluar dari program.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="logout.php">Shut Down</a>
      </div>
    </div>
  </div>
</div>


<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/vue/dist/vue.js'></script>
<script src='https://bstp.sourceforge.io/gallerya.js'></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  const form = document.querySelector('form');
  const submitButton = form.querySelector('input[type="submit"]');
  const bayar = document.getElementById('bayar');
  const totalbayar = document.getElementById('totalbayar');

  submitButton.addEventListener('click', (event) => {
    event.preventDefault();
    if (bayar.value.trim() === '') {
      swal({
        title: 'Pembayaran Ditolak',
        text: 'Jumlah pembayaran harus diisi.',
      });
    } else {

      if (parseInt(bayar.value) < parseInt(totalbayar.value)) {
        swal({
          title: 'Pembayaran Ditolak',
          text: 'Jumlah pembayaran kurang dari total tagihan.',
          icon: 'error',
        });
      } else {
        swal({
          title: 'Konfirmasi Pembayaran',
          text: 'Anda yakin ingin melakukan pembayaran?',
          icon: 'info',
          buttons: ['Batal', 'Bayar'],
          dangerMode: true,
        }).then((willPay) => {
          if (willPay) {
            form.submit();
          }
        });
      }
    }
  });
</script>

<script>
  var currentItemID = null;

  function displayItem(id) {
    $.ajax({
      url: "displayitem.php",
      type: 'POST',
      data: {
        btnMenuID: id
      },
      success: function(output) {
        $("#tblItem").html(output);
        calculateTotal()
      }
    });
  }

  function insertItem() {
    var id = currentItemID;
    var quantity = $("#qty").val();
    $.ajax({
      url: "displayitem.php",
      type: 'POST',
      data: {
        btnMenuItemID: id,
        qty: quantity
      },
      success: function(output) {
        $("#tblOrderList").append(output);
        $("#qtypanel").prop('hidden', true);
        calculateTotal();
        calculate();
        susuk();
      }
    });
    $("#qty").val(1);
  }

  function updateitem(qty) {
    var id = currentItemID;
    var quantity = qty
    $.ajax({
      url: "displayitem.php",
      type: 'POST',
      data: {
        btnMenuItemID: id,
        qty: quantity
      },
      success: function(output) {
        $("#qtypanel").prop('hidden', true);
        calculateTotal();
        calculate();
        susuk();
      }
    });
    $("#qty").val(1);
  }

  function setQty(id) {
    currentItemID = id;
    $("#qtypanel").prop('hidden', false);
    calculateTotal()
  }
  $(document).on('click', '.deleteBtn', function(event) {
    event.preventDefault();
    $(this).closest('tr').remove();
    calculateTotal()
    return false;
  });
</script>

<script type="text/javascript">
  const totalInput = document.getElementById('total');
  const persenInput = document.getElementById('persen');
  const diskonInput = document.getElementById('diskon');

  const totalbayarInput = document.getElementById('totalbayar');
  const bayarInput = document.getElementById('bayar');
  const kembalianInput = document.getElementById('kembalian');
  const kembalianRow = document.getElementById("kembalianRow");


  function calculateTotal() {
    var table = document.getElementById("tblOrderList");
    var rowCount = table.rows.length;
    var total = 0;

    for (var i = 1; i < rowCount; i++) {
      var row = table.rows[i];
      var price = row.querySelector('input[name="harga[]"]').value;
      var qty = row.querySelector('input[name="itemqty[]"]').value;
      total += price * qty;
    }
    document.getElementById("total").value = total;
  }

  function deleteRow() {

    totalbayarInput.value = totalInput.value;
  }

  function calculate() {
    if (persenInput.value > 0) {
      const totalbayar = totalInput.value - (totalInput.value * persenInput.value / 100);
      totalbayarInput.value = parseFloat(totalbayar) || 0;
      diskonInput.value = totalInput.value - totalbayarInput.value;
      const bayar = parseFloat(bayarInput.value) || 0;
      const kembalian = bayar - totalbayar;
      kembalianInput.value = kembalian;
    } else {
      const totalbayar = totalInput.value - diskonInput.value;
      totalbayarInput.value = parseFloat(totalbayar) || 0;
      const bayar = parseFloat(bayarInput.value) || 0;
      const kembalian = bayar - totalbayar;
      kembalianInput.value = kembalian;

    }
  }

  totalInput.addEventListener('input', calculate);
  persenInput.addEventListener('input', calculate);
  diskonInput.addEventListener('input', calculate);
  bayarInput.addEventListener('input', calculate);
  bayarInput.addEventListener('input', susuk);

  function susuk() {
    if (parseInt(document.getElementById('bayar').value) > parseInt(1000)) {
      kembalianRow.style.display = "table-row";
    } else {
      kembalianRow.style.display = "none";
    }
  };
</script>

</html>