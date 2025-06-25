

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Include PHPMailer files (adjust the path as needed)
require 'C:\xampp\htdocs\HMS\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\HMS\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\HMS\PHPMailer\src\SMTP.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_pwd'])) {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    // Store OTP and email in session
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_otp'] = $otp;

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'johnbillbarangan00@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'sxdu lxcj nqvm qcqw'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('johnbillbarangan00@gmail.com', 'Hospital Management System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body    = 'Your OTP for password reset is: <b>' . $otp . '</b>';

        $mail->send();
        // Redirect to OTP entry page
        header("Location: his_doc_otpauth.php");
        exit();
    } catch (Exception $e) {
        // Handle error (show error message or redirect back)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8" />
        <title>Hospital Management Information System -A Super Responsive Information System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured doctor theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
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
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_pwd'])): ?>
            <script>
                <?php
                if (isset($mail) && $mail->isError()) {
                    echo "swal('Error', 'Message could not be sent. Mailer Error: " . addslashes($mail->ErrorInfo) . "', 'error');";
                } else if (isset($otp)) {
                    echo "swal('Success', 'OTP sent to your email!', 'success');";
                }
                ?>
            </script>
        <?php endif; ?>




    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="his_doc_reset_pwd.php">
                                        <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                                    </a>
                                    <p class="text-muted mb-4 mt-3">Enter your email address and we'll send you an OTP to reset your password.</p>
                                </div>

                                <form method="post" >

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button name="reset_pwd" class="btn btn-primary btn-block" type="submit"> Reset Password </button>
                                    </div>
                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_pwd']) && isset($mail) && !$mail->isError()) {
                                        echo "<script>window.location.href = 'his_doc_otpauth.php';</script>";
                                        exit();
                                    }
                                    ?>

                                </form>

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Back to <a href="index.php" class="text-white ml-1"><b>Log in</b></a></p>
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
