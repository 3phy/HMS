<?php
    session_start();
    include('assets/inc/config.php');
    include('assets/inc/checklogin.php');
    check_login();

    if(isset($_GET['pat_id']) && isset($_GET['pat_number'])) {
        $pat_id = intval($_GET['pat_id']);
        $pat_number = $_GET['pat_number'];

        $stmt = $mysqli->prepare("SELECT * FROM his_patients WHERE pat_id = ? AND pat_number = ?");
        $stmt->bind_param('is', $pat_id, $pat_number);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_object();
    } else {    
        echo "<script>alert('No patient selected!');window.close();</script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Inpatient Record</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
            body, .print-container, table, th, td {
                font-size: 32px !important;
                line-height: 1.6 !important;
            }
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }
            .print-container {
                width: 210mm !important;
                min-height: 297mm !important;
                max-width: 210mm !important;
                padding: 40mm 20mm 40mm 20mm !important;
                margin: 0 auto !important;
                border: none !important;
                background: #fff !important;
                box-sizing: border-box !important;
            }
            html, body {
                width: 210mm !important;
                height: 297mm !important;
            }
        }
        .print-container {
            margin: 30px auto;
            max-width: 900px;
            border: none;
            padding: 40px;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <h2 class="text-center mb-4">Inpatient Record</h2>
        <?php if($row) { ?>
        <table class="table table-bordered">
            <tr>
                <th>Patient Name</th>
                <td><?php echo htmlspecialchars($row->pat_fname . ' ' . $row->pat_lname); ?></td>
            </tr>
            <tr>
                <th>Patient Number</th>
                <td><?php echo htmlspecialchars($row->pat_number); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($row->pat_addr); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($row->pat_phone); ?></td>
            </tr>
            <tr>
                <th>Age</th>
                <td><?php echo htmlspecialchars($row->pat_age); ?> Years</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo htmlspecialchars($row->pat_gender); ?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?php echo htmlspecialchars($row->pat_type); ?></td>
            </tr>
            <tr>
                <th>Date Registered</th>
                <td><?php echo htmlspecialchars($row->pat_date_reg); ?></td>
            </tr>
            <!-- Add more fields as needed -->
        </table>
        <?php } else { ?>
            <div class="alert alert-danger">Patient record not found.</div>
        <?php } ?>
        <div class="text-center mt-4 no-print">
            <button onclick="window.print();" class="btn btn-primary">Print</button>
            <button onclick="window.close();" class="btn btn-secondary">Close</button>
        </div>
    </div>
</body>
</html>