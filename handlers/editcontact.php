<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $workphone = $_POST['workphone'];
    $homephone = $_POST['homephone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("CALL EditContacts(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $id, $firstname, $lastname, $birthdate, $workphone, $homephone, $email);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "id" => $id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "birthdate" => $birthdate,
            "workphone" => $workphone,
            "homephone" => $homephone,
            "email" => $email
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update contact."]);
    }

    $stmt->close();
}

$conn->close();
?>
