<?php
session_start();

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_otp'])) {
    // No OTP/email in session, redirect to reset page
    header("Location: his_doc_reset_pwd.php");
    exit();
}

// Track last OTP sent time
if (!isset($_SESSION['otp_last_sent'])) {
    $_SESSION['otp_last_sent'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];
        if ($entered_otp == $_SESSION['reset_otp']) {
            $success = "OTP verified! Please proceed to reset your password.";
            // Optionally redirect to a password reset form here
        } else {
            $error = "The OTP you entered is incorrect. Please try again.";
        }
    }

    if (isset($_POST['resend_otp'])) {
        // Only allow resend if 60 seconds have passed
        if (time() - $_SESSION['otp_last_sent'] >= 60) {
            // Generate new OTP
            $new_otp = rand(100000, 999999);
            $_SESSION['reset_otp'] = $new_otp;
            $_SESSION['otp_last_sent'] = time();

            // Send OTP to email (implement your own mail function)
            // mail($_SESSION['reset_email'], "Your OTP Code", "Your OTP is: $new_otp");

            $success = "A new OTP has been sent to your email.";
        } else {
            $error = "Please wait before requesting a new OTP.";
        }
    }
}

$can_resend = (time() - $_SESSION['otp_last_sent']) >= 60;
$remaining = 60 - (time() - $_SESSION['otp_last_sent']);
if ($remaining < 0) $remaining = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Hospital Management Information System - OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured doc theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/swal.js"></script>
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
                                <p class="text-muted mb-4 mt-3">
                                    Please enter the One-Time Password (OTP) sent to your email to continue.
                                </p>
                            </div>
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php elseif (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="otp">One-Time Password (OTP)</label>
                                    <input class="form-control" name="otp" type="text" id="otp" required placeholder="Enter the OTP">
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button name="verify_otp" class="btn btn-primary btn-block" type="submit">Verify OTP</button>
                                </div>
                                <?php if (isset($success)): ?>
                                    <script>
                                        setTimeout(function() {
                                            window.location.href = "his_doc_changepass.php";
                                        }, 1500);
                                    </script>
                                <?php endif; ?>
                            </form>
                            <form method="post" class="mt-3 text-center">
                                <button id="resendBtn" name="resend_otp" class="btn btn-link" type="submit" <?php if (!$can_resend) echo 'disabled'; ?>>
                                    Resend OTP
                                </button>
                                <span id="timer" class="text-muted" style="display: <?php echo $can_resend ? 'none' : 'inline'; ?>">
                                    (Wait <span id="countdown"><?php echo $remaining; ?></span> seconds)
                                </span>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Back to <a href="index.php" class="text-white ml-1"><b>Log in</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script>
    // Countdown timer for resend OTP
    var canResend = <?php echo $can_resend ? 'true' : 'false'; ?>;
    var remaining = <?php echo $remaining; ?>;
    if (!canResend) {
        var countdown = document.getElementById('countdown');
        var resendBtn = document.getElementById('resendBtn');
        var timer = document.getElementById('timer');
        var interval = setInterval(function() {
            remaining--;
            countdown.textContent = remaining;
            if (remaining <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                timer.style.display = 'none';
            }
        }, 1000);
    }
    </script>
</body>
</html>
