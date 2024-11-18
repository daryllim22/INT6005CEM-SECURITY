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

// Check if the booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_content.php");
    exit();
}

$booking_id = $_GET['id'];

// Fetch booking data
$sql = "SELECT * FROM booking WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "<p style='color:red; text-align:center;'>Booking not found.</p>";
    exit();
}

// Handle form submission to update booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    $update_sql = "UPDATE booking SET name = ?, email = ?, phone = ?, date = ?, time = ?, service = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssssssi", $name, $email, $phone, $date, $time, $service, $booking_id);

    if (mysqli_stmt_execute($update_stmt)) {
        mysqli_stmt_close($update_stmt);
        mysqli_close($conn);
        header("Location: manage_content.php?success=Booking updated successfully.");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Error updating booking: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .edit-form {
            margin: 20px auto;
            text-align: center;
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Edit Booking - Lim's Sports Complex</h1>
    </div>
    <div class="navbar">
        <a href="manage_content.php">Back to Manage Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="edit-form">
        <h3>Edit Booking</h3>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" required><br><br>

            <label for="date">Date:</label>
            <input type="date" name="date" value="<?php echo htmlspecialchars($booking['date']); ?>" required><br><br>

            <label for="time">Time:</label>
            <input type="time" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>" required><br><br>

            <label for="service">Service:</label>
            <select name="service" required>
                <option value="Basketball" <?php if ($booking['service'] == 'Basketball') echo 'selected'; ?>>Basketball</option>
                <option value="Badminton" <?php if ($booking['service'] == 'Badminton') echo 'selected'; ?>>Badminton</option>
            </select><br><br>

            <input type="submit" value="Update Booking">
        </form>
    </div>
</body>
</html>
