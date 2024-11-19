<?php
session_start(); // Start session to track login state

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// If already logged in, redirect to respective dashboard
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: manage_users.php");
        exit();
    } elseif ($_SESSION['role'] == 'user') {
        header("Location: booking_page.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="header">
    <h1>Lim's Sports Complex</h1>
</div>

<center>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
        <input type="button" value="Register" onclick="location.href='register.php'">
    </form>
</center>

<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "sport_facility";

    $conn = mysqli_connect($servername, $db_username, $db_password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch user details
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("SQL Error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Debugging: Log details to console
    echo "<script>console.log('User input password: " . addslashes($password) . "');</script>";
    if ($user) {
        echo "<script>console.log('Stored password hash: " . addslashes($user['password']) . "');</script>";
        echo "<script>console.log('User role: " . addslashes($user['role']) . "');</script>";
    } else {
        echo "<script>console.log('No user found for username: " . addslashes($username) . "');</script>";
    }

    // Check if user exists and the password matches
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: manage_users.php");
            exit();
        } elseif ($user['role'] == 'user') {
            header("Location: booking_page.php");
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>Username or password is incorrect.</div>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
</body>
</html>
