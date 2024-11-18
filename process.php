<?php
// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Create a database connection (replace with your database credentials)
$host = 'localhost';
$db = 'your_database_name';
$name = 'your_name';
$password = 'your_password';

$conn = new PDO("mysql:host=$host;dbname=$db", $name, $password);

// Insert data into the "contacts" table
$sql = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':message', $message);
$stmt->execute();

// Close the database connection
$conn = null;

// Redirect back to the contact form or display a success message
header('Location: contact.php');
?>
