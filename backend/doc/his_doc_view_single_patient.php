<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['doc_id'];
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php');?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php");?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php");?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <?php
            $pat_number=$_GET['pat_number'];
            $pat_id=$_GET['pat_id'];
            $ret="SELECT  * FROM his_patients WHERE pat_id=?";
            $stmt= $mysqli->prepare($ret);
            $stmt->bind_param('i',$pat_id);
            $stmt->execute();
            $res=$stmt->get_result();
            while($row=$res->fetch_object()) {
                $mysqlDateTime = $row->pat_date_joined;
        ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
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
                                <h4 class="page-title"><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>'s Profile</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-xl-4">
                            <div class="card-box text-center">
                                <img src="assets/images/users/patient.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                <div class="text-left mt-3">
                                    <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2"><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2"><?php echo $row->pat_phone;?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Address :</strong> <span class="ml-2"><?php echo $row->pat_addr;?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Date Of Birth :</strong> <span class="ml-2"><?php echo $row->pat_dob;?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Age :</strong> <span class="ml-2"><?php echo $row->pat_age;?> Years</span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Condition :</strong> <span class="ml-2"><?php echo $row->pat_condition;?></span></p>
                                    <hr>
                                    <p class="text-muted mb-2 font-13"><strong>Date Recorded :</strong> <span class="ml-2"><?php echo date("d/m/Y - h:m", strtotime($mysqlDateTime));?></span></p>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xl-8">
                            <div class="card-box text-center">
                                <div class="text-left mt-3">
                                    <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2"><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Patient Type :</strong> <span class="ml-2"><?php echo htmlspecialchars($row->pat_type); ?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Treatment :</strong> <span class="ml-2"><?php echo htmlspecialchars($row->pat_treatment); ?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Referring Unit :</strong> <span class="ml-2"><?php echo htmlspecialchars($row->ref_unit); ?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Patient Condition :</strong> <span class="ml-2"><?php echo htmlspecialchars($row->pat_condition); ?></span></p>
                                    <p class="text-muted mb-2 font-13"><strong>Patient Department :</strong> <span class="ml-2"><?php echo htmlspecialchars($row->pat_dept); ?></span></p>

                                    <?php
                                      $stmt_consults = $mysqli->prepare("SELECT consult_date, consult_notes, consult_checklist, consult_image, checklist_files FROM his_consultations WHERE pat_id = ? ORDER BY consult_date DESC");
                                      $stmt_consults->bind_param("i", $pat_id);
                                      $stmt_consults->execute();
                                      $res_consults = $stmt_consults->get_result();
                                    ?>

                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#fullConsultModal<?php echo $pat_id; ?>">
                                      View Full Consultation
                                    </button>

                                    <div class="modal fade" id="fullConsultModal<?php echo $pat_id; ?>" tabindex="-1" role="dialog" aria-labelledby="fullConsultModalLabel<?php echo $pat_id; ?>" aria-hidden="true">
                                      <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="fullConsultModalLabel<?php echo $pat_id; ?>">
                                              Full Consultations for <?php echo htmlspecialchars($row->pat_fname . ' ' . $row->pat_lname); ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                                            <?php
                                              if ($res_consults->num_rows > 0) {
                                                while ($consult = $res_consults->fetch_object()) {
                                                  ?>
                                                  <div class="consultation-entry mb-4 p-3 border rounded">
                                                    <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($consult->consult_date)); ?></p>
                                                    <p><strong>Notes:</strong><br>
                                                      <pre style="white-space: pre-wrap;"><?php echo htmlspecialchars($consult->consult_notes); ?></pre>
                                                    </p>
                                                    <p><strong>Checklist:</strong></p>
                                                    <?php
                                                      if (!empty($consult->consult_checklist)) {
                                                        $checklist_items = explode(',', $consult->consult_checklist);
                                                        echo '<ul>';
                                                        foreach ($checklist_items as $item) {
                                                          echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
                                                        }
                                                        echo '</ul>';
                                                      } else {
                                                        echo '<p><em>No checklist items.</em></p>';
                                                      }
                                                    ?>
                                                    <?php if (!empty($consult->consult_image) && file_exists($consult->consult_image)) { ?>
                                                      <p><strong>Primary Consultation Image:</strong></p>
                                                      <img src="<?php echo htmlspecialchars($consult->consult_image); ?>" alt="Consultation Image" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                                                    <?php } ?>

                                                    <?php
                                                      if (!empty($consult->checklist_files)) {
                                                        echo '<p><strong>Checklist File Uploads:</strong></p>';
                                                        $files = explode("\n", $consult->checklist_files);
                                                        foreach ($files as $file) {
                                                          $parts = explode(':', $file, 2);
                                                          $label = htmlspecialchars(trim($parts[0]));
                                                          $path = isset($parts[1]) ? trim($parts[1]) : '';
                                                          if (!empty($path) && file_exists($path)) {
                                                            echo "<div class='mb-2'><strong>$label:</strong><br>";
                                                            echo "<img src='" . htmlspecialchars($path) . "' alt='$label Image' style='max-width:100%; height:auto; border:1px solid #ccc; padding:5px;'></div>";
                                                          }
                                                        }
                                                      }
                                                    ?>
                                                  </div>
                                                  <hr>
                                                  <?php
                                                }
                                              } else {
                                                echo '<p>No consultation records available for this patient.</p>';
                                              }
                                            ?>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>
</html>
