// access_control.php
<?php
function check_access($role) {
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role) {
        header("Location: booking_page.php");
        exit();
    }
}
?>
