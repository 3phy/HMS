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
        <title>All Doctor Records</title>
        <link rel="stylesheet" href="assets/css/app.min.css">
        <style>
            @media print {
                .no-print { display: none; }
            }
        </style>
    </head>
    <body>
        <h2>All Doctor Records</h2>
        <hr>
        <table class="table" style="border-collapse:collapse; border:2px solidrgb(0, 0, 0);">
            <thead>
                <tr style="background-color:#D9EAD3;">
                    <th style="border:2px solid; padding:8px;">#</th>
                    <th style="border:2px solid; padding:8px;">Doctor Name</th>
                    <th style="border:2px solid; padding:8px;">Doctor ID</th>
                    <th style="border:2px solid; padding:8px;">Doctor Address</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $ret="SELECT * FROM his_docs ORDER BY doc_fname, doc_lname";
                $stmt= $mysqli->prepare($ret);
                $stmt->execute();
                $res=$stmt->get_result();
                $cnt=1;
                while($row=$res->fetch_object()) {
                    echo "<tr>";
                    echo "<td style='border:2px solid; padding:8px;'>{$cnt}</td>";
                    echo "<td style='border:2px solid; padding:8px;'>{$row->doc_fname} {$row->doc_lname}</td>";
                    echo "<td style='border:2px solid; padding:8px;'>{$row->doc_number}</td>";
                    echo "<td style='border:2px solid; padding:8px;'>{$row->doc_email}</td>";

                    echo "</tr>";
                    $cnt++;
                }
            ?>
            </tbody>
        </table>
        <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
    </body>
    </html>