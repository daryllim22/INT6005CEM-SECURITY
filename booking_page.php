
<!DOCTYPE html>
<html>
<head>
    <title>Booking Page</title>
    <link rel="stylesheet" href="style.css" />
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

    <center>
        <form action="process_booking.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required><br><br>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br><br>
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required><br><br>
            <label for="service">Service:</label>
            <select id="service" name="service" required>
                <option value="Basketball">Basketball</option>
                <option value="Badminton">Badminton</option>
            </select><br><br>
            <input type="submit" value="Book Now">
        </form>
    </center>
</body>
</html>
