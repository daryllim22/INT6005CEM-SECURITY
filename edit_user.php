<?php
session_start();

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "sport_facility";

$conn = mysqli_connect($servername, $db_username, $db_password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch user data
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<p style='color:red; text-align:center;'>User not found.</p>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE user SET username = ?, password = ?, role = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "sssi", $username, $hashed_password, $role, $user_id);
    } else {
        // Update without changing the password
        $update_sql = "UPDATE user SET username = ?, role = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ssi", $username, $role, $user_id);
    }

    if (mysqli_stmt_execute($update_stmt)) {
        mysqli_stmt_close($update_stmt);
        mysqli_close($conn);
        header("Location: manage_users.php?success=User updated successfully.");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Error updating user: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .navbar {
            margin: 20px;
            text-align: center;
        }
        .navbar a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
        }
        form {
            margin: 0 auto;
            width: 50%;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Edit User - Lim's Sports Complex</h1>
    </div>
    <div class="navbar">
        <a href="manage_users.php">Back to Manage Users</a>
        <a href="logout.php">Logout</a>
    </div>
    <center>
        <h2>Edit User</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="password">New Password (leave blank to keep current password):</label>
            <input type="password" name="password">

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>

            <input type="submit" value="Update User">
        </form>
    </center>
</body>
</html>
