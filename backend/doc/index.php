<?php
    session_start();
    include('assets/inc/config.php');
    if(isset($_POST['doc_login']))
    {
        $doc_number = $_POST['doc_number'];
        $doc_pwd = sha1(md5($_POST['doc_pwd']));
        $stmt = $mysqli->prepare("SELECT doc_number, doc_pwd, doc_id, is_active FROM his_docs WHERE doc_number=? AND doc_pwd=?");
$stmt->bind_param('ss', $doc_number, $doc_pwd);
$stmt->execute();
$stmt->bind_result($doc_number, $doc_pwd, $doc_id, $is_active);
$rs = $stmt->fetch();
$_SESSION['doc_id'] = $doc_id;
$_SESSION['doc_number'] = $doc_number;
$stmt->close();
if($rs)
{
    if($is_active != 1) {
        $update_stmt = $mysqli->prepare("UPDATE his_docs SET is_active=1 WHERE doc_id=?");
        $update_stmt->bind_param('i', $doc_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    header("location:his_doc_dashboard.php");
}
        else
        {
            $err = "Access Denied Please Check Your Credentials";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8" />
        <title>Hospital Management System -A Super Responsive Information System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="MartDevelopers" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!--Load Sweet Alert Javascript-->
        
        <script src="assets/js/swal.js"></script>
        <!--Inject SWAL-->
        <?php if(isset($success)) {?>
        <!--This code for injecting an alert-->
                <script>
                            setTimeout(function () 
                            { 
                                swal("Success","<?php echo $success;?>","success");
                            },
                                100);
                </script>

        <?php } ?>

        <?php if(isset($err)) {?>
        <!--This code for injecting an alert-->
                <script>
                            setTimeout(function () 
                            { 
                                swal("Failed","<?php echo $err;?>","error");
                            },
                                100);
                </script>

        <?php } ?>



    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="index.php">
                                        <span>
                                            <a href="/HMS/index.php">
                                                <img src="assets/images/logo-dark.png" alt="" height="22">
                                            </a>
                                        </span>
                                    </a>
                                    <p class="text-muted mb-4 mt-3">Enter your Doctor ID and Password to access Doctor panel.</p>
                                </div>

                                <form method='post' >

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Doctor ID</label>
                                        <input class="form-control" name="doc_number" type="text" id="emailaddress" required="" placeholder="Enter your doctor number">
                                    </div>

                                    <div class="form-group mb-3 position-relative">
                                    <label for="pass">Enter Password</label>
                                    <input class="form-control" name="doc_pwd" type="password" id="pass" required placeholder="Enter Password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="enter_pass"></i>
                                    </span>
                                </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-success btn-block" name="doc_login" type="submit"> Log In </button>
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

                                <!--
                                For Now Lets Disable This 
                                This feature will be implemented on later versions
                                <div class="text-center">
                                    <h5 class="mt-3 text-muted">Sign in with</h5>
                                    <ul class="social-list list-inline mt-3 mb-0">
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github-circle"></i></a>
                                        </li>
                                    </ul>
                                </div> 
                                -->

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p> <a href="his_doc_reset_pwd.php" class="text-white-50 ml-1">Forgot your password?</a></p>
                               <!-- <p class="text-white-50">Don't have an account? <a href="his_admin_register.php" class="text-white ml-1"><b>Sign Up</b></a></p>-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->


        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>