<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sport_facility";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
section_start();
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * from user Where Username='$username' AND Password='$password'";

$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0)
{
    while($row = mysqli_fetch_assoc($result))
    {
        $username = $row['Username'];
        $password = $row['Password'];
        $_SESSION['ID'] = $row['ID'];
        $_SESSION['Username'] = $row['Username'];
        header('location:homepage.html');
        exit();
    }
}


?>