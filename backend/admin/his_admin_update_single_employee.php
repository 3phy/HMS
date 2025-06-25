<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_doc']))
{
    $doc_fname=$_POST['doc_fname'];
    $doc_lname=$_POST['doc_lname'];
    $doc_number=$_GET['doc_number'];
    $doc_email=$_POST['doc_email'];
    $doc_pwd=sha1(md5($_POST['doc_pwd']));

    // Check if a new file is uploaded
    if (!empty($_FILES["doc_dpic"]["name"])) {
        $doc_dpic=$_FILES["doc_dpic"]["name"];
        move_uploaded_file($_FILES["doc_dpic"]["tmp_name"],"../doc/assets/images/users/".$_FILES["doc_dpic"]["name"]);
    } else {
        // Use the existing picture
        $doc_dpic = isset($_POST['existing_doc_dpic']) ? $_POST['existing_doc_dpic'] : '';
    }

    //sql to insert captured values
    $query="UPDATE his_docs SET doc_fname=?, doc_lname=?,  doc_email=?, doc_pwd=?, doc_dpic=? WHERE doc_number = ?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssssss', $doc_fname, $doc_lname, $doc_email, $doc_pwd, $doc_dpic, $doc_number);
    $stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Employee Details Updated";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
		}
?>
<!--End Server Side-->
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
                                            <li class="breadcrumb-item active">Manage Employee</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Update Employee Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <!-- Form row -->
                        <?php
                            $doc_id=$_GET['doc_id'];
                            $ret="SELECT  * FROM his_docs WHERE doc_id=?";
                            $stmt= $mysqli->prepare($ret) ;
                            $stmt->bind_param('i',$doc_id);
                            $stmt->execute() ;//ok
                            $res=$stmt->get_result();
                            //$cnt=1;
                            while($row=$res->fetch_object())
                            {
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4" class="col-form-label">First Name</label>
                                                    <input type="text" required="required" value="<?php echo $row->doc_fname;?>" name="doc_fname" class="form-control" id="inputEmail4" >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                    <input required="required" type="text" value="<?php echo $row->doc_lname;?>" name="doc_lname" class="form-control"  id="inputPassword4">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputAddress" class="col-form-label">Email</label>
                                                    <input required="required" type="email" value="<?php echo $row->doc_email;?>" class="form-control" name="doc_email" id="inputAddress">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="doc_id" class="col-form-label">Doctor ID</label>
                                                    <input type="text" class="form-control" id="doc_id" name="doc_id" value="<?php echo htmlspecialchars($row->doc_number); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputCity" class="col-form-label">Password</label>
                                                    <input type="password" name="doc_pwd" class="form-control" id="inputCity" placeholder="Leave blank to keep current password">
                                                </div> 
                                                
                                                <div class="form-group col-md-6">
                                                    <label for="inputCity" class="col-form-label">Profile Picture</label>
                                                    <input type="file" name="doc_dpic" class="btn btn-success form-control" id="inputCity">
                                                    <?php if (!empty($row->doc_dpic)) : ?>
                                                        <br>
                                                        <img src="../doc/assets/images/users/<?php echo htmlspecialchars($row->doc_dpic); ?>" alt="Current Photo" width="80" height="80">
                                                        <input type="hidden" name="existing_doc_dpic" value="<?php echo htmlspecialchars($row->doc_dpic); ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <button type="submit" name="update_doc" class="ladda-button btn btn-success" data-style="expand-right">Update Employee</button>

                                        </form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                        <?php }?>

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