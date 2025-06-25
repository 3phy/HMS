<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
        if(isset($_POST['add_doc']))
        {
            $doc_fname=$_POST['doc_fname'];
    $doc_lname=$_POST['doc_lname'];
    $doc_number=$_POST['doc_number'];
    $doc_email=$_POST['doc_email'];
    $doc_dept=$_POST['doc_dept'];
    // Double encrypt password: sha1(md5(password))
    $doc_pwd=sha1(md5($_POST['doc_pwd']));
    
    //sql to insert captured values
    $query="INSERT INTO his_docs (doc_fname, doc_lname, doc_number, doc_email, doc_dept, doc_pwd) values(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssssss', $doc_fname, $doc_lname, $doc_number, $doc_email, $doc_dept, $doc_pwd);
    $stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Employee Details Added";
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item active">Add Employee</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Employee Details</h4>
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
                                                    <input type="text" required="required" name="doc_fname" class="form-control" id="inputEmail4" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                    <input required="required" type="text" name="doc_lname" class="form-control"  id="inputPassword4">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputDocNumber" class="col-form-label">Doctor ID</label>
                                                    <input type="text" name="doc_number" class="form-control" id="inputDocNumber" required placeholder="Doctor Number">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputAddress" class="col-form-label">Email</label>
                                                    <input required="required" type="email" class="form-control" name="doc_email" id="inputAddress">
                                                </div>
                                            </div>
                                                

                                            <div class="form-row">
                                                <div class="form-group col-md-6 position-relative">
                                                    <label for="pass">Enter Password</label>
                                                    <input class="form-control" name="doc_pwd" type="password" id="pass" required minlength="8" placeholder="Enter Password">
                                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('pass', this)">
                                                        <i class="mdi mdi-eye-outline" id="enter_pass"></i>
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-6 position-relative">
                                                    <label for="confirm_pass">Confirm Password</label>
                                                    <input class="form-control" name="doc_pwd_confirm" type="password" id="confirm_pass" required minlength="8" placeholder="Confirm Password">
                                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                                        <i class="mdi mdi-eye-outline" id="confirm_pass_icon"></i>
                                                    </span>
                                                </div>
                                                <script>
                                                    // Client-side password match and length validation
                                                    document.querySelector('form').addEventListener('submit', function(e) {
                                                        var pass = document.getElementById('pass').value;
                                                        var confirmPass = document.getElementById('confirm_pass').value;
                                                        if (pass.length < 8) {
                                                            e.preventDefault();
                                                            alert('Password must be at least 8 characters long!');
                                                            return;
                                                        }
                                                        if (pass !== confirmPass) {
                                                            e.preventDefault();
                                                            alert('Passwords do not match!');
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <script>
                                                function togglePassword(fieldId, el) {
                                                    var input = document.getElementById(fieldId);
                                                    if (input.type === "password") {
                                                        input.type = "text";
                                                        el.querySelector('i').classList.remove('mdi-eye-outline');
                                                        el.querySelector('i').classList.add('mdi-eye-off-outline');
                                                    } else {
                                                        input.type = "password";
                                                        el.querySelector('i').classList.remove('mdi-eye-off-outline');
                                                        el.querySelector('i').classList.add('mdi-eye-outline');
                                                    }
                                                }
                                            </script>
                                            <div class="form-group col-md-4">
        <label for="inputCity" class="col-form-label">Department</label>
        <select required="required" name="doc_dept" class="form-control" id="doc_dept" onchange="toggleOtherDept(this)">
            <option value="" disabled selected>Select Department</option>
            <option value="Physical Department">Physical Department</option>
            <option value="Cardiology">Cardiology</option>
            <option value="Neurology">Neurology</option>
            <option value="Pediatrics">Pediatrics</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="doc_dept_other" id="doc_dept_other" class="form-control mt-2" style="display:none;" placeholder="Enter Department">
        <script>
            function toggleOtherDept(select) {
            var otherInput = document.getElementById('doc_dept_other');
            if (select.value === 'Other') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
            }
            // On page load, in case of form resubmission
            document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('doc_dept');
            toggleOtherDept(select);
            });
        </script>
        </select>
    
                                                
                                            </div>
                                            <button type="submit" name="add_doc" class="ladda-button btn btn-success" data-style="expand-right">Add Employee</button>

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