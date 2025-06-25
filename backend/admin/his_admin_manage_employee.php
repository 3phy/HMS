<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

// Handle Delete Employee
if (isset($_POST['confirm_delete'])) {
    $delete_id = intval($_POST['delete_id']);
    $admin_password = $_POST['admin_password'];

    $stmt = $mysqli->prepare("SELECT ad_pwd FROM his_admin WHERE ad_id=?");
    $stmt->bind_param('i', $aid);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if ($db_password === sha1(md5($admin_password))) {
        $adn = "DELETE FROM his_docs WHERE doc_id=?";
        $stmt = $mysqli->prepare($adn);
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $stmt->close();
        $success = "Employee Fired";
    } else {
        $err = "Incorrect password. Deletion not allowed.";
    }
}

// Handle Update Employee (redirect if password correct)
if (isset($_POST['confirm_update'])) {
    $update_id = intval($_POST['doc_id']);
    $admin_password = $_POST['admin_password'];

    $stmt = $mysqli->prepare("SELECT ad_pwd FROM his_admin WHERE ad_id=?");
    $stmt->bind_param('i', $aid);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if ($db_password === sha1(md5($admin_password))) {
        header("location: his_admin_update_single_employee.php?doc_id=" . $update_id);
        exit;
    } else {
        $err = "Incorrect password. Update not allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php'); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">Employee</a></li>
                                        <li class="breadcrumb-item active">Manage Employees</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Employees Details</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <?php if (isset($success)) { ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php } elseif (isset($err)) { ?>
                        <div class="alert alert-danger"><?php echo $err; ?></div>
                    <?php } ?>

                    <!-- Employee Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <option value="InPatients">InPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th data-toggle="true">Name</th>
                                                <th data-hide="phone">ID</th>
                                                <th data-hide="phone">Email</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM his_docs ORDER BY RAND()";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->doc_fname . " " . $row->doc_lname; ?></td>
                                                    <td><?php echo $row->doc_number; ?></td>
                                                    <td><?php echo $row->doc_email; ?></td>
                                                    <td>
                                                        <!-- View -->
                                                        <a href="his_admin_view_single_employee.php?doc_id=<?php echo $row->doc_id; ?>&doc_number=<?php echo $row->doc_number; ?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>

                                                        <!-- Update -->
                                                        <a href="#" class="badge badge-primary" data-toggle="modal" data-target="#updateModal<?php echo $row->doc_id; ?>"><i class="mdi mdi-check-box-outline"></i> Update</a>
                                                        <div class="modal fade" id="updateModal<?php echo $row->doc_id; ?>" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <form method="post" action="his_admin_manage_employee.php" autocomplete="off">
                                                                    <input type="hidden" name="doc_id" value="<?php echo $row->doc_id; ?>">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Confirm Update</h5>
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>To confirm update, please enter your password:</p>
                                                                            <input type="password" name="admin_password" class="form-control" placeholder="Password" required>
                                                                            <p class="text-warning mt-2">Update this employee record?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" name="confirm_update" class="btn btn-primary">Yes, I want to Update</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <!-- Delete -->
                                                        <a href="#" class="badge badge-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row->doc_id; ?>"><i class="mdi mdi-trash-can-outline"></i> Delete</a>
                                                        <div class="modal fade" id="deleteModal<?php echo $row->doc_id; ?>" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <form method="post" action="his_admin_manage_employee.php" autocomplete="off">
                                                                    <input type="hidden" name="delete_id" value="<?php echo $row->doc_id; ?>">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Confirm Deletion</h5>
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>To confirm deletion, please enter your password:</p>
                                                                            <input type="password" name="admin_password" class="form-control" placeholder="Password" required>
                                                                            <p class="text-danger mt-2">Are you sure you want to delete this employee record?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, I want to Delete</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php $cnt++; } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rightbar-overlay"></div>

        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/libs/footable/footable.all.min.js"></script>
        <script src="assets/js/pages/foo-tables.init.js"></script>
        <script src="assets/js/app.min.js"></script>
    </div>
</body>

</html>