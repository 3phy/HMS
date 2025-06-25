<?php
require 'C:\xampp\htdocs\HMS\vendor\autoload.php'; // for PHPMailer and dompdf

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;

include('assets/inc/config.php');

if (isset($_POST['send_pdf'])) {
    $pat_id = intval($_POST['pat_id']);
    $email = $_POST['pat_email'];

    // Get patient data
    $stmt = $mysqli->prepare("SELECT * FROM his_patients WHERE pat_id=?");
    $stmt->bind_param("i", $pat_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $patient = $res->fetch_assoc();

    // Get consultations
    $stmt2 = $mysqli->prepare("SELECT * FROM his_consultations WHERE pat_id=?");
    $stmt2->bind_param("i", $pat_id);
    $stmt2->execute();
    $consults = $stmt2->get_result();

    // Generate PDF content
    $html = "<h2>Patient Profile: {$patient['pat_fname']} {$patient['pat_lname']}</h2>";
    $html .= "<p>Email: {$email}</p>";
    $html .= "<p>Phone: {$patient['pat_phone']}</p>";
    $html .= "<p>Address: {$patient['pat_addr']} Municipality: {$patient['pat_mun ']}</p>";
    $html .= "<p>Birthday: {$patient['pat_dob']} Age: {$patient['pat_age']}</p>";
    $html .= "<p>Condition: {$patient['pat_condition']}</p>";
    $html .= "<p>Treatment: {$patient['pat_treatment']}</p>";
    $html .= "<p>Referral Unit: {$patient['ref_unit']}</p>";

    $html .= "<hr><h3>Consultations</h3>";

    while ($c = $consults->fetch_assoc()) {
        $html .= "<p><strong>Date:</strong> {$c['consult_date']}</p>";
        $html .= "<p><strong>Notes:</strong><br>" . nl2br(htmlspecialchars($c['consult_notes'])) . "</p>";
        $html .= "<p><strong>Checklist:</strong> {$c['consult_checklist']}</p><hr>";
    }

    // Create PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->render();
    $pdf = $dompdf->output();
    $filename = "Patient_{$pat_id}_Details.pdf";

    // Send Email with PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Change this
        $mail->SMTPAuth   = true;
        $mail->Username   = 'johnbillbarangan00@gmail.com'; // Change this
        $mail->Password   = 'sxdu lxcj nqvm qcqw';    // Change this
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('johnbillbarangan00@gmail.com', 'Hospital Records');
        $mail->addAddress($email, $patient['pat_fname'] . ' ' . $patient['pat_lname']);

        $mail->isHTML(true);
        $mail->Subject = 'Your Patient Record from Hospital';
        $mail->Body    = 'Attached is the PDF version of your medical record and consultations.';
        $mail->addStringAttachment($pdf, $filename);

        $mail->send();
        echo "<script>alert('Email sent successfully!'); window.history.back();</script>";
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>
