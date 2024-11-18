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

// Check if an update form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $booking_id = $_POST['booking_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    // Update the booking in the database
    $sql = "UPDATE booking SET name = ?, email = ?, phone = ?, date = ?, time = ?, service = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $email, $phone, $date, $time, $service, $booking_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color:green; text-align:center;'>Booking updated successfully.</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error updating booking: " . mysqli_error($conn) . "</p>";
    }

    mysqli_stmt_close($stmt);
}

// Fetch all bookings
$sql = "SELECT * FROM booking";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage Booking</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .edit-form {
            margin: 20px auto;
            text-align: center;
            width: 50%;
        }
    </style>
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
    <center><h2>Manage Bookings</h2></center>

    <?php
    if (isset($_GET['success'])) {
        echo "<p style='color:green; text-align:center;'>" . htmlspecialchars($_GET['success']) . "</p>";
    }
    ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Service</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['time'] . "</td>";
                echo "<td>" . $row['service'] . "</td>";
                echo "<td>
                        <a href='edit_booking.php?id=" . $row['id'] . "'>Edit</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No bookings found.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Check if "Edit" link was clicked
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];

        // Fetch booking data for the given ID
        $edit_sql = "SELECT * FROM booking WHERE id = ?";
        $edit_stmt = mysqli_prepare($conn, $edit_sql);
        mysqli_stmt_bind_param($edit_stmt, "i", $edit_id);
        mysqli_stmt_execute($edit_stmt);
        $edit_result = mysqli_stmt_get_result($edit_stmt);
        $edit_row = mysqli_fetch_assoc($edit_result);

        if ($edit_row) {
            ?>
            <div class="edit-form">
                <h3>Edit Booking</h3>
                <form method="POST" action="manage_content.php">
                    <input type="hidden" name="booking_id" value="<?php echo $edit_row['id']; ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo $edit_row['name']; ?>" required><br><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $edit_row['email']; ?>" required><br><br>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" value="<?php echo $edit_row['phone']; ?>" required><br><br>
                    <label for="date">Date:</label>
                    <input type="date" name="date" value="<?php echo $edit_row['date']; ?>" required><br><br>
                    <label for="time">Time:</label>
                    <input type="time" name="time" value="<?php echo $edit_row['time']; ?>" required><br><br>
                    <label for="service">Service:</label>
                    <select name="service" required>
                        <option value="Basketball" <?php if ($edit_row['service'] == 'Basketball') echo 'selected'; ?>>Basketball</option>
                        <option value="Badminton" <?php if ($edit_row['service'] == 'Badminton') echo 'selected'; ?>>Badminton</option>
                    </select><br><br>
                    <input type="submit" name="update_booking" value="Update Booking">
                </form>
            </div>
            <?php
        }

        mysqli_stmt_close($edit_stmt);
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
