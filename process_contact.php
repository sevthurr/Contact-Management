<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $birthdate = $_POST["birthdate"];
    $workphone = $_POST["workphone"];
    $homephone = $_POST["homephone"];
    $email = $_POST["email"];

// Prepare and execute stored procedure
    $stmt = $conn->prepare("CALL AddNewContact(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $birthdate, $workphone, $homephone, $email);

    if ($stmt->execute()) {
        echo "New contact added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

}

// Close connection
$conn->close();
// Redirect back to the main page
header("Location: listcontacts.php");
exit();


?>
