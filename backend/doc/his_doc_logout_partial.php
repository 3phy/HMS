<?php
    session_start();
    include('assets/inc/config.php');
    if(isset($_SESSION['doc_id'])) {
        $update = $mysqli->prepare("UPDATE his_docs SET is_active=0, last_active=NOW() WHERE doc_id=?");
        $update->bind_param('i', $_SESSION['doc_id']);
        $update->execute();
    }
    unset($_SESSION['doc_id']);
    unset($_SESSION['doc_number']);
    session_destroy();
    header("Location: his_doc_logout.php");
    exit;
?>