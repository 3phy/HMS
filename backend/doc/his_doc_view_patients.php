<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['doc_id'];

  // Handle consultation submission if POST request
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_consultation'])) {
      $pat_id = intval($_POST['pat_id']);
      $consult_notes = $_POST['consult_notes'];
      $checklist = isset($_POST['consult_checklist']) ? implode(", ", $_POST['consult_checklist']) : "";

      // Upload main image
      $target_dir = "uploads/consultations/";
      if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
      $image_name = basename($_FILES["consult_image"]["name"]);
      $target_file = $target_dir . time() . "_" . $image_name;

      if (!empty($_FILES["consult_image"]["tmp_name"])) {
          move_uploaded_file($_FILES["consult_image"]["tmp_name"], $target_file);
      } else {
          $target_file = "";
      }

      // Handle checklist file uploads
      $checklist_files = [];
      $upload_fields = [
          'X-ray' => 'xray_file_' . $pat_id,
          'MRI' => 'mri_file_' . $pat_id,
          'EMG- NCV' => 'emg_file_' . $pat_id,
          'CT Scan' => 'ctscan_file_' . $pat_id,
      ];

      foreach ($upload_fields as $label => $input_name) {
          if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
              $file_tmp = $_FILES[$input_name]['tmp_name'];
              $file_name = time() . '_' . basename($_FILES[$input_name]['name']);
              $file_dest = $target_dir . $file_name;

              if (move_uploaded_file($file_tmp, $file_dest)) {
                  $checklist_files[] = "$label: $file_dest";
              }
          }
      }

      $checklist_files_str = implode("\n", $checklist_files);

      // Insert into database with checklist_files
      $stmt = $mysqli->prepare("INSERT INTO his_consultations (pat_id, consult_notes, consult_checklist, consult_image, checklist_files) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("issss", $pat_id, $consult_notes, $checklist, $target_file, $checklist_files_str);
      $stmt->execute();
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php');?>

<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include('assets/inc/nav.php');?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include("assets/inc/sidebar.php");?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">View Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Patient Details</h4>
                        </div>
                    </div>
                </div>     
                <!-- end page title --> 

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title"></h4>
                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-12 text-sm-center form-inline" >
                                        <div class="form-group mr-2" style="display:none">
                                            <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                <option value="">Show all</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                                <option value="Discharge">Discharge</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form method="GET" class="form-inline mb-3">
                                <label class="mr-2">Filter by date:</label>
                                <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
                                <span class="mr-2">to</span>
                                <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                <a href="his_doc_view_patients.php" class="btn btn-secondary btn-sm ml-2">Reset</a>
                            </form>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                    <thead>
                                        <tr>
                                            <th>Patient #</th>
                                            <th data-toggle="true">Name</th>
                                            <th data-hide="phone">Patient ID</th>
                                            <th data-hide="phone">Address</th>
                                            <th data-hide="phone">Phone</th>
                                            <th data-hide="phone">Age</th>
                                            <th data-hide="phone">Category</th>
                                            <th data-hide="phone">Action</th>
                                        </tr>
                                    </thead>

                                    <?php
                                    $filter = $_GET['filter'] ?? '';
                                    $start_date = $_GET['start_date'] ?? '';
                                    $end_date = $_GET['end_date'] ?? '';
                                    $params = [];
                                    $types = '';
                                    $where = [];

                                    if ($filter == 'monthly') {
                                        $where[] = "MONTH(pat_date_joined) = MONTH(CURRENT_DATE()) AND YEAR(pat_date_joined) = YEAR(CURRENT_DATE())";
                                    } elseif ($filter == 'yearly') {
                                        $where[] = "YEAR(pat_date_joined) = YEAR(CURRENT_DATE())";
                                    }

                                    if (!empty($start_date)) {
                                        $where[] = "DATE(pat_date_joined) >= ?";
                                        $params[] = $start_date;
                                        $types .= 's';
                                    }
                                    if (!empty($end_date)) {
                                        $where[] = "DATE(pat_date_joined) <= ?";
                                        $params[] = $end_date;
                                        $types .= 's';
                                    }

                                    $baseQuery = "SELECT * FROM his_patients";
                                    if (count($where) > 0) {
                                        $baseQuery .= " WHERE " . implode(' AND ', $where);
                                    }
                                    $baseQuery .= " ORDER BY pat_date_joined DESC";

                                    $stmt = $mysqli->prepare($baseQuery);
                                    if (!empty($params)) {
                                        $stmt->bind_param($types, ...$params);
                                    }
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    $cnt=1;
                                    if ($res->num_rows > 0) {
                                        while($row=$res->fetch_object())
                                        {
                                    ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $row->pat_id;?></td>
                                                <td><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?></td>
                                                <td><?php echo $row->pat_number;?></td>
                                                <td><?php echo $row->pat_addr;?></td>
                                                <td><?php echo $row->pat_phone;?></td>
                                                <td><?php echo $row->pat_age;?> Years</td>
                                                <td><?php echo $row->pat_type;?></td>
                                                <td>
                                                    <a href="his_doc_view_single_patient.php?pat_id=<?php echo $row->pat_id;?>&&pat_number=<?php echo $row->pat_number;?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#consultModal<?php echo $row->pat_id;?>">
                                                        <i class="mdi mdi-plus"></i> Consult
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    <?php 
                                            $cnt = $cnt +1 ; 
                                        ?>
                                        <!-- Consultation Modal -->
                                        <div class="modal fade" id="consultModal<?php echo $row->pat_id;?>" tabindex="-1" role="dialog" aria-labelledby="consultModalLabel<?php echo $row->pat_id;?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="consultModalLabel<?php echo $row->pat_id;?>">Add Consultation - <?php echo $row->pat_fname . ' ' . $row->pat_lname;?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="pat_id" value="<?php echo $row->pat_id;?>">
                                                            <div class="form-group">
                                                                <label for="consult_notes">Consultation Notes</label>
                                                                <textarea name="consult_notes" id="consult_notes" class="form-control" required></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Checklist</label><br>    
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <label class="mb-0 mr-2">
                                                                        <input type="checkbox" name="consult_checklist[]" value="X-ray" onchange="toggleFileInput(this, 'xray_file_<?php echo $row->pat_id;?>')"> X-ray
                                                                    </label>
                                                                    <input type="file" name="xray_file_<?php echo $row->pat_id;?>" id="xray_file_<?php echo $row->pat_id;?>" class="form-control-file ml-2" style="width:auto; display:none;">
                                                                </div>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <label class="mb-0 mr-2">
                                                                        <input type="checkbox" name="consult_checklist[]" value="MRI" onchange="toggleFileInput(this, 'mri_file_<?php echo $row->pat_id;?>')"> MRI
                                                                    </label>
                                                                    <input type="file" name="mri_file_<?php echo $row->pat_id;?>" id="mri_file_<?php echo $row->pat_id;?>" class="form-control-file ml-2" style="width:auto; display:none;">
                                                                </div>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <label class="mb-0 mr-2">
                                                                        <input type="checkbox" name="consult_checklist[]" value="EMG- NCV" onchange="toggleFileInput(this, 'emg_file_<?php echo $row->pat_id;?>')"> EMG- NCV
                                                                    </label>
                                                                    <input type="file" name="emg_file_<?php echo $row->pat_id;?>" id="emg_file_<?php echo $row->pat_id;?>" class="form-control-file ml-2" style="width:auto; display:none;">
                                                                </div>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <label class="mb-0 mr-2">
                                                                        <input type="checkbox" name="consult_checklist[]" value="CT Scan" onchange="toggleFileInput(this, 'ctscan_file_<?php echo $row->pat_id;?>')"> CT Scan
                                                                    </label>
                                                                    <input type="file" name="ctscan_file_<?php echo $row->pat_id;?>" id="ctscan_file_<?php echo $row->pat_id;?>" class="form-control-file ml-2" style="width:auto; display:none;">
                                                                </div>
                                                                <label>
                                                                    <input type="checkbox" id="custom_check_<?php echo $row->pat_id;?>" onclick="toggleCustomInput<?php echo $row->pat_id;?>()">
                                                                    Other (Use a comma '<b style="font-size: 45px;">,</b>' to separate multiple entries)
                                                                </label>
                                                                <script>
                                                                    function toggleFileInput(checkbox, fileInputId) {
                                                                        var fileInput = document.getElementById(fileInputId);
                                                                        if (checkbox.checked) {
                                                                            fileInput.style.display = 'block';
                                                                        } else {
                                                                            fileInput.style.display = 'none';
                                                                            fileInput.value = '';
                                                                        }
                                                                    }
                                                                </script>
                                                                <input type="text" name="consult_checklist[]" id="custom_input_<?php echo $row->pat_id;?>" class="form-control mt-2" style="display:none;" placeholder="Enter custom..." autocomplete="off">
                                                            </div>
                                                            <script>
                                                                function toggleOtherInput<?php echo $row->pat_id;?>() {
                                                                    var check = document.getElementById('other_check_<?php echo $row->pat_id;?>');
                                                                    var input = document.getElementById('other_input_<?php echo $row->pat_id;?>');
                                                                    input.style.display = check.checked ? 'block' : 'none';
                                                                    if (!check.checked) input.value = '';
                                                                }
                                                                function toggleCustomInput<?php echo $row->pat_id;?>() {
                                                                    var check = document.getElementById('custom_check_<?php echo $row->pat_id;?>');
                                                                    var input = document.getElementById('custom_input_<?php echo $row->pat_id;?>');
                                                                    input.style.display = check.checked ? 'block' : 'none';
                                                                    if (!check.checked) input.value = '';
                                                                }
                                                            </script>
                                                            <script>
                                                                function toggleOtherInput<?php echo $row->pat_id;?>() {
                                                                    var check = document.getElementById('other_check_<?php echo $row->pat_id;?>');
                                                                    var input = document.getElementById('other_input_<?php echo $row->pat_id;?>');
                                                                    input.style.display = check.checked ? 'block' : 'none';
                                                                    if (!check.checked) input.value = '';
                                                                }
                                                            </script>
                                                            <div class="form-group">
                                                                <label for="consult_image">Upload Image</label>
                                                                <input type="file" name="consult_image" id="consult_image" class="form-control-file">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="submit_consultation" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                        $cnt = $cnt +1 ; 
                                        } // end while
                                    } // end if rows
                                    ?>

                                    <tfoot>
                                        <tr class="active">
                                            <td colspan="8">
                                                <div class="text-right">
                                                    <ul class="pagination pagination-rounded justify-content-end footable-pagination mb-0"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end .table-responsive-->

                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->



    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- Footable js -->
<script src="assets/libs/footable/footable.all.min.js"></script>

<!-- Init js -->
<script src="assets/js/pages/foo-tables.init.js"></script>

<!-- App js -->
<script src="assets/js/app.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
