<?php
session_start();
include('assets/inc/config.php');

if (!isset($_SESSION['reset_email'])) {
    header("Location: his_admin_reset_pwd.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_pass'])) {
    $email = $_SESSION['reset_email'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass !== $confirm_pass) {
        $err = "The passwords you entered do not match.";
    } else {
        // Hash the password like others
        $hashed_pass = sha1(md5($new_pass));
        // Update password in the admin table
        $stmt = $mysqli->prepare("UPDATE his_admin SET ad_pwd=? WHERE ad_email=?");
        $stmt->bind_param('ss', $hashed_pass, $email);
        if ($stmt->execute()) {
            $success = "Your password has been updated successfully.";
            // Unset session variables for security
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_otp']);
            header("refresh:2;url=index.php");
        } else {
            $err = "Unable to update password. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Reset Admin Password - Hospital Management System</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/swal.js"></script>
    <?php if(isset($success)) { ?>
        <script>
            setTimeout(function () { swal("Success","<?php echo $success;?>","success"); }, 100);
        </script>
    <?php } ?>
    <?php if(isset($err)) { ?>
        <script>
            setTimeout(function () { swal("Error","<?php echo $err;?>","error"); }, 100);
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
                                <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                                <p class="text-muted mb-4 mt-3">Please enter your new password below to reset your admin account.</p>
                            </div>
                            <form method="post">
                                <div class="form-group mb-3 position-relative">
                                    <label for="new_pass">New Password</label>
                                    <input class="form-control" name="new_pass" type="password" id="new_pass" required placeholder="New password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('new_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="icon_new_pass"></i>
                                    </span>
                                </div>
                                <div class="form-group mb-3 position-relative">
                                    <label for="confirm_pass">Confirm New Password</label>
                                    <input class="form-control" name="confirm_pass" type="password" id="confirm_pass" required placeholder="Re-enter new password">
                                    <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('confirm_pass', this)">
                                        <i class="mdi mdi-eye-outline" id="icon_confirm_pass"></i>
                                    </span>
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
                                <div class="form-group mb-0 text-center">
                                    <button name="change_pass" class="btn btn-primary btn-block" type="submit">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Return to <a href="index.php" class="text-white ml-1"><b>Admin Login</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
