<?php
session_start();

// Ensure only admins can access this page
/*if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php?error=Access denied.");
    exit();
}*/

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "sport_facility";

$conn = mysqli_connect($servername, $db_username, $db_password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user ID is provided in the query string
if (!isset($_GET['id'])) {
    header("Location: manage_users.php?error=No user ID provided.");
    exit();
}

$user_id = $_GET['id'];

// Delete the user from the database
$sql = "DELETE FROM user WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: manage_users.php?success=User deleted successfully.");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: manage_users.php?error=Error deleting user.");
        exit();
    }
} else {
    mysqli_close($conn);
    header("Location: manage_users.php?error=Failed to prepare SQL statement.");
    exit();
}
?>
