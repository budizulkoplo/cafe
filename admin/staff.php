<?php include("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: login.php");

if (!empty($_POST['role'])) {

    $role = $sqlconnection->real_escape_string($_POST['role']);

    $staffID = $sqlconnection->real_escape_string($_POST['staffID']);

    $updateRoleQuery = "UPDATE tbl_staff SET role = '{$role}'  WHERE staffID = {$staffID}  ";

    if ($sqlconnection->query($updateRoleQuery) === TRUE) {

        echo "";
    } else {

        echo "someting wong";

        echo $sqlconnection->error;
    }
} ?> <?php include 'header.php'; ?> <div class="col-12 col-md-12 p-3 p-md-5 bg-white border border-white text-center"> <i class="fas fa-user-circle fa-4x"></i>

    <h1><strong>RESTO CAFE STAFF</strong></h1>

    <hr>

    <p>restaurant cafe staff list.</p>

    <div class="col-12 p-3 p-md-5">

        <form action="addstaff.php" method="POST" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

            <div class="input-group">
                <?php if (in_array($_SESSION['user_role'], array("admin", "super admin"))) { ?>
                    <select name="storeid" class="form-control"> <?php $roleQuery = "SELECT storeid, `desc` FROM tbl_store";
                                                                    if ($res = $sqlconnection->query($roleQuery)) {
                                                                        while ($id = $res->fetch_array(MYSQLI_ASSOC)) {
                                                                            echo "<option value='" . $id['storeid'] . "'>" . ucfirst($id['desc']) . "</option>";
                                                                        }
                                                                    } ?> </select>
                <?php  } else { ?>
                    <input type="hidden" required="required" name="storeid" class="form-control" value="<?php echo $_SESSION['storeid']; ?>" aria-label="Add" aria-describedby="basic-addon2">
                <?php } ?>

                <select name="staffrole" class="form-control"> <?php $roleQuery = "SELECT role FROM tbl_role";

                                                                if ($res = $sqlconnection->query($roleQuery)) {

                                                                    if ($res->num_rows == 0) {

                                                                        echo "no role";
                                                                    }

                                                                    while ($role = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                        echo "<option value='" . $role['role'] . "'>" . ucfirst($role['role']) . "</option>";
                                                                    }
                                                                } ?> </select>

                <input type="text" required="required" name="staffname" class="form-control" placeholder="Staff Name" aria-label="Add" aria-describedby="basic-addon2">

                <input type="text" required="required" name="password" class="form-control" placeholder="Password" aria-label="Add" aria-describedby="basic-addon2">

                <div class="input-group-append"> <button type="submit" name="addstaff" class="btn btn-primary"> (+) </button></div>

            </div>

        </form>

    </div>

    <div class="col-12 p-3 p-md-5">

        <table class="table table-responsive table-lg shadow text-center" id="dataTable" width="100%" cellspacing="0">

            <tr class="bg-dark text-white">

                <th>#</th>

                <th>User</th>

                <th>Status</th>

                <th>Account</th>

                <th></th>

            </tr> <?php
                    if ($_SESSION['storeid'] == 0) {
                        $storeid = '%';
                    } else {
                        $storeid = $_SESSION['storeid'];
                    }

                    $displayStaffQuery = "SELECT * FROM tbl_staff WHERE storeid like '{$storeid}'";

                    if ($result = $sqlconnection->query($displayStaffQuery)) {

                        if ($result->num_rows == 0) {

                            echo "<td colspan='4'>There are currently no staff.</td>";
                        }

                        $staffno = 1;

                        while ($staff = $result->fetch_array(MYSQLI_ASSOC)) { ?> <tr class="text-center">

                        <td><?php echo $staffno++; ?></td>

                        <td><?php echo $staff['username']; ?></td> <?php if ($staff['status'] == "Online") {

                                                                        echo "<td><span class=\"badge badge-success\">Online</span></td>";
                                                                    }

                                                                    if ($staff['status'] == "Offline") {

                                                                        echo "<td><span class=\"badge badge-secondary\">Offline</span></td>";
                                                                    } ?> <td>

                            <form method="POST"> <input type="hidden" name="staffID" value="<?php echo $staff['staffID']; ?>" /> <select name="role" class="form-control" onchange="this.form.submit()"> <?php $roleQuery = "SELECT role FROM tbl_role";

                                                                                                                                                                                                            if ($res = $sqlconnection->query($roleQuery)) {

                                                                                                                                                                                                                if ($res->num_rows == 0) {

                                                                                                                                                                                                                    echo "no role";
                                                                                                                                                                                                                }

                                                                                                                                                                                                                while ($role = $res->fetch_array(MYSQLI_ASSOC)) {

                                                                                                                                                                                                                    if ($role['role'] == $staff['role']) echo "<option selected='selected' value='" . $staff['role'] . "'>" . ucfirst($staff['role']) . "</option>";

                                                                                                                                                                                                                    else echo "<option value='" . $role['role'] . "'>" . ucfirst($role['role']) . "</option>";
                                                                                                                                                                                                                }
                                                                                                                                                                                                            } ?> </select> <noscript><input type="submit" value="Submit"></noscript></form>

                        </td>

                        <td class="text-center"><a href="deletestaff.php?staffID=<?php echo $staff['staffID']; ?>" class="btn btn-sm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">

                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />

                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />

                                </svg></a></td>

                    </tr> <?php }
                    } else {

                        echo $sqlconnection->error;

                        echo "Something wrong.";
                    } ?>

        </table>

    </div>

</div>

</div> <?php include 'footer.php'; ?>