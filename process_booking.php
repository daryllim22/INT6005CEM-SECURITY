<!DOCTYPE html>
<html>
<head>
    <title>Booking Page</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .center-content {
            text-align: center;
            margin: 20px auto;
            width: 60%; /* Adjust width as needed */
        }
        table {
            margin: 0 auto; /* Center align the table */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lim's Sports Complex</h1>
    </div>
      
    <div class="navbar">
        <a href="booking_page.php">Bookings</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="login.php?logout=true">Logout</a>
    </div>
    <br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $service = $_POST['service'];

        // Database connection details
        $servername = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $database = "sport_facility";

        // Create connection
        $conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO booking (name, email, phone, date, time, service) VALUES ('$name', '$email', '$phone', '$date', '$time', '$service')";

        // Execute the query
        $insertSuccess = false;
        if ($conn->query($sql) === true) {
            $insertSuccess = true;
        }

        // Close the database connection
        $conn->close();
        ?>
        <div class="center-content">
            <h2>Booking Confirmed</h2>
            <?php if ($insertSuccess): ?>
                <p>Your booking has been saved successfully.</p>
            <?php else: ?>
                <p>There was an issue saving your booking. Please try again later.</p>
            <?php endif; ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($name); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($email); ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo htmlspecialchars($phone); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo htmlspecialchars($date); ?></td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td><?php echo htmlspecialchars($time); ?></td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td><?php echo htmlspecialchars($service); ?></td>
                </tr>
            </table>
        </div>
    <?php
    } else {
        // Redirect to the booking page if accessed directly without form submission
        header('Location: booking_page.php');
        exit;
    }
    ?>
</body>
</html>
