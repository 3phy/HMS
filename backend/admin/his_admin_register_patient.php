<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_patient']))
		{
			$pat_fname=$_POST['pat_fname'];
			$pat_lname=$_POST['pat_lname'];
			$pat_number=$_POST['pat_number'];
            $pat_phone=$_POST['pat_phone'];
            $pat_type=$_POST['pat_type'];
            $pat_addr=$_POST['pat_addr'];
            $pat_age = $_POST['pat_age'];
            $pat_dob = $_POST['pat_dob'];
            $pat_condition = $_POST['pat_condition']; // renamed from ailment
            $pat_dept = $_POST['pat_dept'];
            $ref_unit = $_POST['ref_unit'];
            $pat_treatment = $_POST['pat_treatment'];
            $pat_gender = $_POST['pat_gender'];
            $pat_date_reg = date('Y-m-d H:i:s');
            $pat_mun = $_POST['pat_mun'];
            

            //sql to insert captured values
			$query="INSERT INTO his_patients (
    pat_fname, pat_condition, pat_lname, pat_age, pat_dob, pat_number, pat_phone, 
    pat_type, pat_addr, pat_dept, ref_unit, pat_treatment, pat_gender, pat_date_reg ,pat_mun
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";

$stmt = $mysqli->prepare($query);
$rc = $stmt->bind_param(
    'ssssssssssssss',
    $pat_fname, $pat_condition, $pat_lname, $pat_age, $pat_dob, $pat_number,
    $pat_phone, $pat_type, $pat_addr, $pat_dept, $ref_unit, $pat_treatment, $pat_gender, $pat_date_reg, $pat_mun
);

            $stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Details Added";
			}
			else {
				$err = "Please Try Again Or Try Later";
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
                                            <li class="breadcrumb-item active">Add Patient</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Patient Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <!-- Form row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4" class="col-form-label">First Name</label>
                                                    <input type="text" required="required" name="pat_fname" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                    <input required="required" type="text" name="pat_lname" class="form-control"  id="inputPassword4" placeholder="Patient`s Last Name">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="pat_dob" class="col-form-label">Date Of Birth</label>
                                                    <div class="input-group">
                                                        <input type="text" required="required" name="pat_dob" class="form-control" id="pat_dob" placeholder="DD/MM/YYYY" autocomplete="off">
                                                        <div class="input-group-append">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var dobInput = document.getElementById('pat_dob');
                                                    var calendarBtn = document.getElementById('dob_calendar_btn');
                                                    // Use browser datepicker if available
                                                    dobInput.type = 'date';
                                                    dobInput.placeholder = 'YYYY-MM-DD';
                                                    calendarBtn.addEventListener('click', function() {
                                                        dobInput.focus();
                                                        dobInput.showPicker && dobInput.showPicker();
                                                    });
                                                    // Optional: convert value to DD/MM/YYYY on blur
                                                    dobInput.addEventListener('blur', function() {
                                                        var val = dobInput.value;
                                                        if(val && /^\d{4}-\d{2}-\d{2}$/.test(val)) {
                                                            var parts = val.split('-');
                                                            dobInput.value = parts[2] + '/' + parts[1] + '/' + parts[0];
                                                        }
                                                    });
                                                    // Optional: convert back to YYYY-MM-DD on focus
                                                    dobInput.addEventListener('focus', function() {
                                                        var val = dobInput.value;
                                                        if(val && /^\d{2}\/\d{2}\/\d{4}$/.test(val)) {
                                                            var parts = val.split('/');
                                                            dobInput.value = parts[2] + '-' + parts[1] + '-' + parts[0];
                                                        }
                                                    });
                                                });
                                                </script>
                                                <div class="form-group col-md-3">
                                                    <label for="pat_age" class="col-form-label">Age</label>
                                                    <input required="required" type="text" name="pat_age" class="form-control" id="pat_age" placeholder="Patient's Age" readonly>
                                                </div>
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
                                                            if(!isNaN(age) && age >= 0) {
                                                                ageInput.value = age;
                                                            } else {
                                                                ageInput.value = '';
                                                            }
                                                        } else {
                                                            ageInput.value = '';
                                                        }
                                                    });
                                                });
                                                </script>
                                            </div>

                                            
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                <label for="inputAddress" class="col-form-label">Address</label>
                                                <input required="required" type="text" class="form-control" name="pat_addr" id="inputAddress" placeholder="Patient's Address">
                                            </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputMunicipality" class="col-form-label">Municipality</label>
                                                    <input required="required" type="text" class="form-control" name="pat_mun" id="inputMunicipality" placeholder="Enter Municipality">
                                                </div>
                                                
                                            </div>

                                            <div class="form-row">
    <div class="form-group col-md-4">
        <label for="inputCity" class="col-form-label">Mobile Number</label>
        <input required="required" type="text" name="pat_phone" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-4">
        <label for="inputCity" class="col-form-label">Patient Condition</label> <!-- changed label -->
        <input required="required" type="text" name="pat_condition" class="form-control" id="inputCity"> <!-- changed name -->
    </div>
    <div class="form-group col-md-4">
        <label for="inputCity" class="col-form-label">Gender</label> <!-- changed label -->
        <select required="required" name="pat_gender" class="form-control" id="pat_gender">
            <option value="" disabled selected>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <input type="hidden" name="pat_type" value="Active">

    <div class="form-group col-md-12">
        <label for="pat_treatment" class="col-form-label">Treatment</label>
        <textarea name="pat_treatment" class="form-control" placeholder="Treatment details" rows="3"></textarea>
    </div>
    
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="pat_dept" class="col-form-label">Patient Department</label>
        <input required="required" type="text" name="pat_dept" class="form-control" placeholder="e.g., Orthopedic Department">
    </div>
    <div class="form-group col-md-6">
        <label for="ref_unit" class="col-form-label">Referring Unit / Doctor</label>
        <input required="required" type="text" name="ref_unit" class="form-control" placeholder="e.g., Dr. Smith / Emergency Unit">
    </div>
</div>

<!-- Existing Patient Number (hidden) -->
<div class="form-group col-md-2" style="display:none">
    <?php 
        $length = 5;    
        $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
    ?>
    <label for="inputZip" class="col-form-label">Patient Number</label>
    <input type="text" name="pat_number" value="<?php echo $patient_number;?>" class="form-control" id="inputZip">
</div>

<?php
    // Get the current date and time in Y-m-d H:i:s format
    $pat_date_reg = date('Y-m-d H:i:s');
?>
<input type="hidden" name="pat_date_reg" value="<?php echo $pat_date_reg; ?>">
<button type="submit" name="add_patient" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient</button>
</form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
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