<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_profile']))
		{
			$doc_fname=$_POST['doc_fname'];
			$doc_lname=$_POST['doc_lname'];
			$doc_id=$_SESSION['doc_id'];
            $doc_email=$_POST['doc_email'];
           // $doc_pwd=sha1(md5($_POST['doc_pwd']));
            $doc_dpic=$_FILES["doc_dpic"]["name"];
		    move_uploaded_file($_FILES["doc_dpic"]["tmp_name"],"assets/images/users/".$_FILES["doc_dpic"]["name"]);

            //sql to insert captured values
			$query="UPDATE his_docs SET doc_fname=?, doc_lname=?,  doc_email=?, doc_dpic=? WHERE doc_id = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssi', $doc_fname, $doc_lname, $doc_email, $doc_dpic, $doc_id);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Profile Updated";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
        }
        //Change Password
        if(isset($_POST['update_pwd']))
		{
            $doc_number=$_SESSION['doc_number'];
            $doc_pwd=sha1(md5($_POST['doc_pwd']));//double encrypt 
            
            //sql to insert captured values
			$query="UPDATE his_docs SET doc_pwd =? WHERE doc_number = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('si', $doc_pwd, $doc_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Password Updated";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
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
                <?php include('assets/inc/sidebar.php');?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
                $doc_id=$_SESSION['doc_id'];
                $ret="SELECT * FROM  his_docs where doc_id=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('i',$doc_id);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
            ?>
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
                                                <li class="breadcrumb-item active">Profile</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title"><?php echo $row->doc_fname;?> <?php echo $row->doc_lname;?>'s Profile</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- end page title -->

                            <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="../doc/assets/images/users/<?php echo $row->doc_dpic;?>" class="rounded-circle avatar-lg img-thumbnail"
                                        alt="profile-image">

                                    
                                    <div class="text-centre mt-3">
                                        
                                        <p class="text-muted mb-2 font-13"><strong>Employee Full Name :</strong> <span class="ml-2"><?php echo $row->doc_fname;?> <?php echo $row->doc_lname;?></span></p>
                                        <p class="text-muted mb-2 font-13"><strong>Employee ID :</strong> <span class="ml-2"><?php echo $row->doc_number;?></span></p>
                                        <p class="text-muted mb-2 font-13"><strong>Employee Email :</strong> <span class="ml-2"><?php echo $row->doc_email;?></span></p>


                                    </div>

                                </div> <!-- end card-box -->

                            </div> <!-- end col-->

                                <div class="col-lg-8 col-xl-8">
                                    <div class="card-box">
                                        <ul class="nav nav-pills navtab-bg nav-justified">
                                            <li class="nav-item">
                                                <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    Update Profile
                                                </a>
                                            </li>
                                            
                                            <li class="nav-item">
                                                <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                    Change Password
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="aboutme">

                                            <form method="post" enctype="multipart/form-data">
                                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name</label>
                                                                <input type="text" name="doc_fname" class="form-control" id="firstname"
                                                                    value="<?php echo isset($_POST['doc_fname']) && $_POST['doc_fname'] !== '' ? htmlspecialchars($_POST['doc_fname']) : htmlspecialchars($row->doc_fname); ?>"
                                                                    placeholder="<?php echo $row->doc_fname;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name</label>
                                                                <input type="text" name="doc_lname" class="form-control" id="lastname"
                                                                    value="<?php echo isset($_POST['doc_lname']) && $_POST['doc_lname'] !== '' ? htmlspecialchars($_POST['doc_lname']) : htmlspecialchars($row->doc_lname); ?>"
                                                                    placeholder="<?php echo $row->doc_lname;?>">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="useremail">Email Address</label>
                                                                <input type="email" name="doc_email" class="form-control" id="useremail"
                                                                    value="<?php echo isset($_POST['doc_email']) && $_POST['doc_email'] !== '' ? htmlspecialchars($_POST['doc_email']) : htmlspecialchars($row->doc_email); ?>"
                                                                    placeholder="<?php echo $row->doc_email;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="useremail">Profile Picture</label>
                                                                <input type="file" name="doc_dpic" class="form-control btn btn-success" id="useremail" >
                                                            </div>
                                                        </div>
                                                        
                                                    </div> <!-- end row -->

                                                    <!--
                                                    <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-office-building mr-1"></i> Company Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="companyname">Company Name</label>
                                                                <input type="text" class="form-control" id="companyname" placeholder="Enter company name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cwebsite">Website</label>
                                                                <input type="text" class="form-control" id="cwebsite" placeholder="Enter website url">
                                                            </div>
                                                        </div> 
                                                    </div> 

                                                    <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-earth mr-1"></i> Social</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-fb">Facebook</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-facebook-square"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-fb" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-tw">Twitter</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-tw" placeholder="Username">
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div> 

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-insta">Instagram</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-insta" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-lin">Linkedin</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-lin" placeholder="Url">
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div> 

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-sky">Skype</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-skype"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-sky" placeholder="@username">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="social-gh">Github</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fab fa-github"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="social-gh" placeholder="Username">
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>  -->
                                                    
                                                    <div class="text-right">
                                                        <button type="submit" name="update_profile" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                    </div>
                                                </form>


                                            </div> <!-- end tab-pane -->
                                            <!-- end about me section content -->

                                           

                                            <div class="tab-pane" id="settings">
                                                <form method="post">
                                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 position-relative">
                                    <label for="confirm_pass">Old Password</label>
                                    <input class="form-control" name="confirm_pass" type="password" id="confirm_pass" required placeholder="Re-enter new password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="icon_confirm_pass"></i>
                                    </span>
                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 position-relative">
                                    <label for="confirm_pass">New Password</label>
                                    <input class="form-control" name="confirm_pass" type="password" id="confirm_pass" required placeholder="Re-enter new password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="icon_confirm_pass"></i>
                                    </span>
                                </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="form-group mb-3 position-relative">
                                    <label for="confirm_pass">Confirm New Password</label>
                                    <input class="form-control" name="confirm_pass" type="password" id="confirm_pass" required placeholder="Re-enter new password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="icon_confirm_pass"></i>
                                    </span>
                                </div>

                                                    <div class="text-right">
                                                        <button type="submit" name="update_pwd" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Update Password</button>
                                                    </div>

                                                   <script>
                                    function togglePassword(fieldId, el) {
                                        var input = document.getElementById(fieldId);
                                        var icon = el.querySelector('i');
                                        if (input.type === "password") {
                                            input.type = "text";
                                            icon.classList.remove('mdi-eye-outline');
                                            icon.classList.add('mdi-eye-off-outline');
                                        } else {
                                            input.type = "password";
                                            icon.classList.remove('mdi-eye-off-outline');
                                            icon.classList.add('mdi-eye-outline');
                                        }
                                    }
                                </script>
                                                </form>
                                            </div>
                                            <!-- end settings content-->

                                        </div> <!-- end tab-content -->
                                    </div> <!-- end card-box-->

                                </div> <!-- end col -->
                            </div>
                            <!-- end row-->

                        </div> <!-- container -->

                    </div> <!-- content -->

          
                </div>
            <?php }?>
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