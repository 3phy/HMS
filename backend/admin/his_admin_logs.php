<?php
    session_start();
    include('assets/inc/config.php');
    include('assets/inc/checklogin.php');
    check_login();
    $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php include("assets/inc/head.php");?>
<body>
<div id="wrapper">
        <?php include('assets/inc/nav.php');?>
        <?php include('assets/inc/sidebar.php');?>
        <div class="content-page">
                <div class="content">
                        <div class="container-fluid">
                                <!-- Dashboard Title -->
                                <div class="row">
                                        <div class="col-12">
                                                <div class="page-title-box">
                                                        <h4 class="page-title">Hospital Management System Logs and Charts</h4>
                                                </div>
                                        </div>
                                </div>
                                <!-- User Login Chart -->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card-box">
                                            <h4 class="header-title mb-3">User Login Status</h4>
                                            <form method="get" class="mb-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Filter:</label>
                                                    <div class="col-sm-9">
                                                        <select name="period" class="form-control" onchange="this.form.submit()">
                                                            <option value="day" <?php if(!isset($_GET['period']) || $_GET['period']=='day') echo 'selected'; ?>>This Day</option>
                                                            <option value="month" <?php if(isset($_GET['period']) && $_GET['period']=='month') echo 'selected'; ?>>This Month</option>
                                                            <option value="year" <?php if(isset($_GET['period']) && $_GET['period']=='year') echo 'selected'; ?>>This Year</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                            <canvas id="userStatusChart" height="120"></canvas>
                                            <?php
                                                // Determine filter period
                                                $period = isset($_GET['period']) ? $_GET['period'] : 'day';
                                                $where = '';
                                                if ($period == 'day') {
                                                    $where = "WHERE DATE(last_active) = CURDATE()";
                                                } elseif ($period == 'month') {
                                                    $where = "WHERE YEAR(last_active) = YEAR(CURDATE()) AND MONTH(last_active) = MONTH(CURDATE())";
                                                } elseif ($period == 'year') {
                                                    $where = "WHERE YEAR(last_active) = YEAR(CURDATE())";
                                                }
                                                // Count online and offline users for the selected period
                                                $online = 0; $offline = 0;
                                                $result = "SELECT is_active FROM his_docs $where";
                                                $stmt = $mysqli->prepare($result);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while($row = $res->fetch_assoc()) {
                                                    if (!empty($row['is_active']) && ($row['is_active'] == 1 || $row['is_active'] === true)) {
                                                        $online++;
                                                    } else {
                                                        $offline++;
                                                    }
                                                }
                                                $stmt->close();
                                            ?>
                                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                            <script>
                                                var ctx = document.getElementById('userStatusChart').getContext('2d');
                                                var userStatusChart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: ['Online', 'Offline'],
                                                        datasets: [{
                                                            label: 'Users',
                                                            data: [<?php echo $online; ?>, <?php echo $offline; ?>],
                                                            backgroundColor: ['#28a745', '#adb5bd']
                                                        }]
                                                    },
                                                    options: {
                                                        responsive: true,
                                                        plugins: {
                                                            legend: { display: false }
                                                        },
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                precision: 0
                                                            }
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                        <!-- Last Active Users Table -->
                                        <div class="col-xl-6">
                                                <div class="card-box">
                                                        <h4 class="header-title mb-3">Recently Active Users</h4>
                                                        <div class="table-responsive">
                                                                <table class="table table-borderless table-hover table-centered m-0">
                                                                        <thead class="thead-light">
                                                                                <tr>
                                                                                        <th>Name</th>
                                                                                        <th>Email</th>
                                                                                        <th>Last Active</th>
                                                                                        <th>Status</th>
                                                                                </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                                $ret = "SELECT doc_fname, doc_lname, doc_email, is_active, last_active FROM his_docs ORDER BY last_active DESC LIMIT 10";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute();
                                                                                $res = $stmt->get_result();
                                                                                while($row = $res->fetch_object()) {
                                                                                        echo "<tr>";
                                                                                        echo "<td>{$row->doc_fname} {$row->doc_lname}</td>";
                                                                                        echo "<td>{$row->doc_email}</td>";
                                                                                        echo "<td>".(!empty($row->last_active) ? date('M d, Y H:i', strtotime($row->last_active)) : 'Never')."</td>";
                                                                                        echo "<td>";
                                                                                        if (!empty($row->is_active) && ($row->is_active == 1 || $row->is_active === true)) {
                                                                                                echo '<span style="color: #28a745;"><i class="fas fa-circle"></i> Online</span>';
                                                                                        } else {
                                                                                                echo '<span style="color: #adb5bd;"><i class="fas fa-circle"></i> Offline</span>';
                                                                                        }
                                                                                        echo "</td>";
                                                                                        echo "</tr>";
                                                                                }
                                                                                $stmt->close();
                                                                        ?>
                                                                        </tbody>
                                                                </table>
                                                        </div>
                                                </div>
                                        </div>

                                        <!-- Update Logs Table -->
                                        <div class="col-xl-12">
                                            <div class="card-box">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h4 class="header-title mb-3 mb-0">Patient Update Logs</h4>
                                                    <div class="mt-0">
                                                        <a href="his_admin_print_logs.php" target="_blank" class="btn btn-primary">
                                                            <i class="mdi mdi-printer"></i> Print Records
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-hover table-centered m-0">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Log ID</th>
                                                                <th>Patient ID</th>
                                                                <th>Updated By</th>
                                                                <th>Changed Fields</th>
                                                                <th>Old Values</th>
                                                                <th>New Values</th>
                                                                <th>Updated At</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $ret = "SELECT log_id, pat_id, updated_by, changed_fields, old_values, new_values, updated_at FROM his_update_logs ORDER BY updated_at DESC LIMIT 20";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute();
                                                            $res = $stmt->get_result();
                                                            while($row = $res->fetch_object()) {
                                                                echo "<tr>";
                                                                echo "<td>".htmlspecialchars($row->log_id)."</td>";
                                                                echo "<td>".htmlspecialchars($row->pat_id)."</td>";
                                                                echo "<td>".htmlspecialchars($row->updated_by)."</td>";
                                                                echo "<td>".htmlspecialchars($row->changed_fields)."</td>";
                                                                echo "<td>".htmlspecialchars($row->old_values)."</td>";
                                                                echo "<td>".htmlspecialchars($row->new_values)."</td>";
                                                                echo "<td>".date('M d, Y H:i', strtotime($row->updated_at))."</td>";
                                                                echo "</tr>";
                                                            }
                                                            $stmt->close();
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                
                        </div>
                </div>
        </div>
</div>
<!-- END wrapper -->
<div class="rightbar-overlay"></div>
<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
</body>
</html>
