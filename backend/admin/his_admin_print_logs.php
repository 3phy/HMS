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
        <title>Patient Update Logs</title>
        <link rel="stylesheet" href="assets/css/app.min.css">
        <style>
            @media print {
                .no-print { display: none; }
            }
            .table th, .table td {
                border: 2px solid #000 !important;
                padding: 8px !important;
            }
            .thead-light th {
                background-color: #D9EAD3 !important;
            }
        </style>
    </head>
    <body>
        <h2>Patient Update Logs</h2>
        <hr>
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
        <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
    </body>
    </html>