<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Active and Inactive Patient Records</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>    
</head>
<body>
    <form method="GET" class="form-inline mb-3">
        <label class="mr-2">Filter by date:</label>
        <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
        <span class="mr-2">to</span>
        <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
        <a href="his_admin_print_all_outpatients.php" class="btn btn-secondary btn-sm ml-2">Reset</a>
    </form>
    <?php
    // Prepare date filter if set
    $where = "pat_type IN ('Active', 'Inactive')";
    $params = [];
    $types = "";

    if (!empty($_GET['start_date'])) {
        $where .= " AND pat_date_reg >= ?";
        $params[] = $_GET['start_date'];
        $types .= "s";
    }
    if (!empty($_GET['end_date'])) {
        $where .= " AND pat_date_reg <= ?";
        $params[] = $_GET['end_date'];
        $types .= "s";
    }

    // Prepare query with date filter
    $ret = "SELECT * FROM his_patients WHERE $where ORDER BY pat_fname, pat_lname";
    $stmt = $mysqli->prepare($ret);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $cnt = 1;
    ?>
    <h2>All Active and Inactive Patient Records</h2>
    <hr>
    <table class="table table-bordered" style="border-collapse:collapse; border:2px solid rgb(0, 0, 0);">
        <thead>
            <tr style="background-color:#D9EAD3;">
                <th style="border:2px solid; padding:8px;">#</th>
                <th style="border:2px solid; padding:8px;">Patient Name</th>
                <th style="border:2px solid; padding:8px;">Patient Number</th>
                <th style="border:2px solid; padding:8px;">Patient Address</th>
                <th style="border:2px solid; padding:8px;">Patient Phone</th>
                <th style="border:2px solid; padding:8px;">Patient Age</th>
                <th style="border:2px solid; padding:8px;">Patient Type</th>
                <th style="border:2px solid; padding:8px;">Date Registered</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while($row = $res->fetch_object()) {
                echo "<tr style='background-color:#D9EAD3;'>";
                echo "<td style='border:2px solid; padding:8px;'>{$cnt}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_fname} {$row->pat_lname}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_number}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_addr}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_phone}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_age} Years</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_type}</td>";
                echo "<td style='border:2px solid; padding:8px;'>{$row->pat_date_reg}</td>";
                echo "</tr>";
                $cnt++;
            }
        ?>
        </tbody>
    </table>
    <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
</body>
</html>