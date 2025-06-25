<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');

if (isset($_POST['update_patient'])) {
    $pat_id = $_GET['pat_id']; // or hidden input

    // Fetch old data before update
    $old_query = "SELECT * FROM his_patients WHERE pat_id=?";
    $old_stmt = $mysqli->prepare($old_query);
    $old_stmt->bind_param('i', $pat_id);
    $old_stmt->execute();
    $old_result = $old_stmt->get_result();
    $old_data = $old_result->fetch_assoc();

    // Prepare new data from POST
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_gender = $_POST['pat_gender'];
    $pat_dob = $_POST['pat_dob'];
    $pat_age = $_POST['pat_age'];
    $pat_phone = $_POST['pat_phone'];
    $pat_condition = $_POST['pat_condition'];
    $pat_type = $_POST['pat_type'];
    $pat_treatment = $_POST['pat_treatment'];
    $ref_unit = $_POST['ref_unit'];
    $pat_dept = $_POST['pat_dept'];

    $query = "UPDATE his_patients SET 
        pat_fname=?, pat_lname=?, pat_gender=?, pat_dob=?, pat_age=?, pat_phone=?,
        pat_condition=?, pat_type=?, pat_treatment=?, ref_unit=?, pat_dept=?
        WHERE pat_id=?";
        
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param(
        'sssssssssssi',
        $pat_fname, $pat_lname, $pat_gender, $pat_dob, $pat_age, $pat_phone,
        $pat_condition, $pat_type, $pat_treatment, $ref_unit, $pat_dept, $pat_id
    );

    if ($stmt->execute()) {
        // Map DB field names to readable labels
        $field_labels = [
            'pat_fname' => 'First Name',
            'pat_lname' => 'Last Name',
            'pat_gender' => 'Gender',
            'pat_dob' => 'Date of Birth',
            'pat_age' => 'Age',
            'pat_phone' => 'Mobile Number',
            'pat_condition' => 'Patient Condition',
            'pat_type' => "Patient's Type",
            'pat_treatment' => 'Treatment',
            'ref_unit' => 'Referring Unit / Doctor',
            'pat_dept' => 'Patient Department'
        ];

        // Compare old and new values
        $new_data = [
            'pat_fname' => $pat_fname,
            'pat_lname' => $pat_lname,
            'pat_gender' => $pat_gender,
            'pat_dob' => $pat_dob,
            'pat_age' => $pat_age,
            'pat_phone' => $pat_phone,
            'pat_condition' => $pat_condition,
            'pat_type' => $pat_type,
            'pat_treatment' => $pat_treatment,
            'ref_unit' => $ref_unit,
            'pat_dept' => $pat_dept
        ];

        $changed_fields = [];
        $old_values = [];
        $new_values = [];

        foreach ($new_data as $field => $new_value) {
            $old_value = isset($old_data[$field]) ? $old_data[$field] : null;
            if ($old_value != $new_value) {
                $label = isset($field_labels[$field]) ? $field_labels[$field] : $field;
                $changed_fields[] = $label;
                $old_values[] = $old_value;
                $new_values[] = $new_value;
            }
        }

        // Only log if there are changes
        if (!empty($changed_fields)) {
            // Get admin's full name
            $updated_by = '';
            if (isset($_SESSION['ad_id']) && !empty($_SESSION['ad_id'])) {
                $admin_id = $_SESSION['ad_id'];
                $admin_stmt = $mysqli->prepare("SELECT ad_fname, ad_lname FROM his_admin WHERE ad_id=? LIMIT 1");
                $admin_stmt->bind_param('i', $admin_id);
                $admin_stmt->execute();
                $admin_stmt->bind_result($ad_fname, $ad_lname);
                if ($admin_stmt->fetch()) {
                    $updated_by = trim($ad_fname . ' ' . $ad_lname);
                }
                $admin_stmt->close();
            }

            // Fallback to session username
            if (empty($updated_by) && isset($_SESSION['admin_uname'])) {
                $updated_by = $_SESSION['admin_uname'];
            }

            // Insert log
            $log_query = "INSERT INTO his_update_logs (pat_id, updated_by, changed_fields, old_values, new_values) VALUES (?, ?, ?, ?, ?)";
            $log_stmt = $mysqli->prepare($log_query);
            $changed_fields_json = json_encode($changed_fields);
$old_values_json = json_encode($old_values);
$new_values_json = json_encode($new_values);

$log_stmt->bind_param(
    'issss',
    $pat_id,
    $updated_by,
    $changed_fields_json,
    $old_values_json,
    $new_values_json
);

            $log_stmt->execute();
        }

        $success = "Patient details updated successfully!";
    } else {
        $err = "Failed to update patient details. Please try again.";
    }
}
?>

<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
    
    <!--Head-->
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
                                            <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                            <li class="breadcrumb-item active">Manage Patients</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Update Patient Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                                                <!-- Form row -->
                                                <!--LETS GET DETAILS OF SINGLE PATIENT GIVEN THEIR ID-->
                                                <?php
                                                    $pat_id = $_GET['pat_id'];
                                                    $ret = "SELECT  * FROM his_patients WHERE pat_id=?";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->bind_param('i', $pat_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    while ($row = $res->fetch_object()) {
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="header-title">Fill all fields</h4>
                                                                <!--Add Patient Form-->
                                                                <form method="post">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-5">
                                                                            <label for="inputEmail4" class="col-form-label">First Name</label>
                                                                            <input type="text" required="required" value="<?php echo $row->pat_fname;?>" name="pat_fname" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                                                        </div>
                                                                        <div class="form-group col-md-5">
                                                                            <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                                            <input required="required" type="text" value="<?php echo $row->pat_lname;?>" name="pat_lname" class="form-control"  id="inputPassword4" placeholder="Patient`s Last Name">
                                                                        </div>
                                                                    </div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="inputCity" class="col-form-label">Gender</label>
        <select required="required" name="pat_gender" class="form-control" id="pat_gender">
            <option value="" disabled>Select Gender</option>
            <option value="Male" <?php echo ($row->pat_gender == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($row->pat_gender == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="pat_dob" class="col-form-label">Date Of Birth</label>
        <div class="input-group">
            <input type="date" required="required" name="pat_dob" class="form-control" id="pat_dob" 
                value="<?php echo $row->pat_dob; ?>" placeholder="YYYY-MM-DD">
            <div class="input-group-append">
            </div>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="pat_age" class="col-form-label">Age</label>
        <input type="number" name="pat_age" class="form-control" id="pat_age"
            value="<?php echo $row->pat_age; ?>" required="required" min="0">
    </div>
</div>
  <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="inputCity" class="col-form-label">Mobile Number</label>
                                                    <input required="required" type="text" value="<?php echo $row->pat_phone;?>" name="pat_phone" class="form-control" id="inputCity">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="inputCity" class="col-form-label">Patient Condition</label>
                                                    <input required="required" type="text" value="<?php echo $row->pat_condition;?>" name="pat_condition" class="form-control" id="inputCity">
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="inputState" class="col-form-label">Patient's Type</label>
                                                    <select id="inputState" required="required" name="pat_type" class="form-control">
                                                        <option value="" disabled selected>Choose</option>
                                                        <option value="Active" <?php echo ($row->pat_type == 'Active') ? 'selected' : ''; ?>>Active</option>
<option value="Inactive" <?php echo ($row->pat_type == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
<option value="Discharge" <?php echo ($row->pat_type == 'Discharge') ? 'selected' : ''; ?>>Discharge</option>

                                                    </select>
                                                </div>
                                                   <div class="form-group col-md-12">
        <label for="pat_treatment" class="col-form-label">Treatment</label>
        <textarea name="pat_treatment" class="form-control" placeholder="Treatment details" rows="3"><?php echo isset($row->pat_treatment) ? $row->pat_treatment : ''; ?></textarea>
    </div>
                                            
                                            </div>
                                            <div class="form-row">
    <div class="form-group col-md-6">
        <label for="ref_unit" class="col-form-label">Referring Unit / Doctor</label>
        <input required="required" type="text" name="ref_unit" class="form-control" placeholder="e.g., Dr. Smith / Emergency Unit" value="<?php echo isset($row->ref_unit) ? $row->ref_unit : ''; ?>">
    </div>
    <div class="form-group col-md-6">
        <label for="pat_dept" class="col-form-label">Patient Department</label>
        <input required="required" type="text" name="pat_dept" class="form-control" placeholder="e.g., Orthopedic Department" value="<?php echo isset($row->pat_dept) ? $row->pat_dept : ''; ?>">
    </div>

      <button type="submit" name="update_patient" class="ladda-button btn btn-success" data-style="expand-right">Update Patient</button>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dobInput = document.querySelector('input[name="pat_dob"]');
    var ageInput = document.getElementById('pat_age');
    dobInput.addEventListener('change', function() {
        var dob = dobInput.value;
        // Expecting format YYYY-MM-DD
        var parts = dob.split('-');
        if(parts.length === 3) {
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10) - 1; // JS months 0-based
            var day = parseInt(parts[2], 10);
            var birthDate = new Date(year, month, day);
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            ageInput.value = age;
        }
    });
});
// Logging is now handled at the top PHP block after the update.
</script>

                                        </form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <?php  }?>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

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

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <!-- Loading buttons js -->
        <script src="assets/libs/ladda/spin.js"></script>
        <script src="assets/libs/ladda/ladda.js"></script>

        <!-- Buttons init js-->
        <script src="assets/js/pages/loading-btn.init.js"></script>
        
    </body>

</html>