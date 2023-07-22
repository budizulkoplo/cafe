<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if ($_SESSION['user_role'] != "waiters") {

  echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");

  exit();
} ?> <?php include 'header.php'; ?><div class="col-12 col-md-12 p-3 p-md-3 bg-white border border-white">

  <h1 class="text-center"><strong>Update Order</strong></h1>

  <hr>

  <p class="text-center">Order Display</p>

  <table id="tblCurrentOrder" class="table bg-light" width="100%" cellspacing="0">
    <thead class="bg-light text-dark">
      <tr>
        <td colspan="5" class="bg-secondary"><input type="text" required="required" onchange="refreshTableOrder()" class="form-control" id="orderid" name="orderid" placeholder="Nomer Order"></td>
      </tr>

      <th>Menu</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>Total</th>
      <th>Action</th>
    </thead>

    <tbody id="tblorderlist">
    </tbody>


    <tr>
      <td colspan='3'>
        Jumlah
      </td>
      <td align="right" colspan='2'><input style="width: 100%; text-align: right; color:red;" type='text' id="total" class="form-control" value='<?php echo   $_SESSION['total']; ?>' name='total' readonly /></td>
    </tr>
    <tr>
      <td colspan='3'>
        Diskon
      </td>
      <td align="right"><input style="text-align: right;" type='text' id="diskon" class="form-control" name='diskon' autocomplete="off" />
      </td>
      <td align="right"><input style="text-align: right;" type='text' id="persen" class="form-control" name='persen' placeholder="%" autocomplete="off" /></td>
    </tr>
    <tr>
      <td colspan="3">
        Total<br><span class="fontkecil">(Total harus dibayar total harga - diskon)</span>
      </td>
      <td align="right" colspan='2'><input style="text-align: right; color:green;" onchange=calculate() type='text' id="totalbayar" class="form-control" name='totalbayar' readonly /></td>
    </tr>
    <tr>
      <td colspan='3'>
        Bayar<br><span class="fontkecil"> (Jumlah uang dibayarkan)</span>
      </td>
      <td align="right" colspan='2'><input style="text-align: right; color:green;" type='text' id="bayar" class="form-control" name='bayar' autocomplete="off" /></td>
    </tr>
    <tr id="kembalianRow">
      <td colspan='3'>
        Kembali
      </td>
      <td align="right" colspan='2'><input style="text-align: right; color:blue;" onchange=susuk() type='text' id="kembalian" class="form-control" name='kembalian' readonly />
        <input style="width: 100%; text-align: right; color:blue;" type='hidden' id="oke" class="form-control" name='oke' value='bayar' />
      </td>
    </tr>
  </table>
  <input class="btn btn-dark btn-lg col-12" type="submit" name="update" onclick="cetakulang()" value="UPDATE">

</div>

<script src="vendor/jquery/jquery.min.js"></script>

<!-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

<script type="text/javascript">
  function updateqty(orderid, itemid, qty) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "updateordersave.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        refreshTableOrder()
      }
    };
    xhr.send("id=" + orderid + "&itemid=" + itemid + "&qty=" + qty + "&action=update");
  }

  function deleteRow(orderid, itemid) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "updateordersave.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        refreshTableOrder()
      }
    };
    xhr.send("id=" + orderid + "&itemid=" + itemid + "&action=delete");
  }


  function cetakulang() {
    orderid = document.getElementById('orderid').value;
    total = document.getElementById('total').value;
    diskon = document.getElementById('diskon').value;
    bayar = document.getElementById('bayar').value;
    kembalian = document.getElementById('kembalian').value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "updateordersave.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        refreshTableOrder()
        window.open("struk.php?orderid=" + document.getElementById('orderid').value);
      }
    };
    xhr.send("id=" + orderid + "&total=" + total + "&diskon=" + diskon + "&kembalian=" + kembalian + "&bayar=" + bayar + "&action=cetak");
    location.reload();
    // window.open("struk.php?orderid=" + document.getElementById('orderid').value);
  }

  function refreshTableOrder() {
    $("#tblorderlist").load("updateorderlistv2.php?orderid=" + document.getElementById('orderid').value);
  }

  const ambiltotal = document.getElementById('ambiltotal');
  const totalInput = document.getElementById('total');
  const persenInput = document.getElementById('persen');
  const diskonInput = document.getElementById('diskon');

  const totalbayarInput = document.getElementById('totalbayar');
  const bayarInput = document.getElementById('bayar');
  const kembalianInput = document.getElementById('kembalian');
  const kembalianRow = document.getElementById("kembalianRow");




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

<?php include 'footer.php'; ?>