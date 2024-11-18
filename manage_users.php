<?php
session_start();

Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php?error=Access denied.");
    exit();
}

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "sport_facility";

$conn = mysqli_connect($servername, $db_username, $db_password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];

    // Delete the user from the database
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $delete_message = "User deleted successfully.";
        } else {
            $delete_error = "Error deleting user: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $delete_error = "Failed to prepare SQL statement.";
    }
}

// Fetch all users
$sql = "SELECT id, username, role, created_at FROM user";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching users: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard - Lim's Sports Complex</h1>
    </div>
    <div class="navbar">
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_content.php">Manage Booking</a>
        <a href="logout.php">Logout</a>
    </div>
    <center>
        <h2>Manage Users</h2>

        <!-- Display success or error messages -->
        <?php
        if (isset($delete_message)) {
            echo "<p style='color:green; text-align:center;'>" . htmlspecialchars($delete_message) . "</p>";
        }
        if (isset($delete_error)) {
            echo "<p style='color:red; text-align:center;'>" . htmlspecialchars($delete_error) . "</p>";
        }
        ?>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['role'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>
                            <a href='edit_user.php?id=" . $row['id'] . "'>Edit</a> | 
                            <a href='manage_users.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found.</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </table>
    </center>
</body>
</html>
