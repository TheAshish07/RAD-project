<?php
session_start();
include 'connection.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Perform validations here if required

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit(); // Exit the script if passwords do not match
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and bind parameters to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO formdata (name, phone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to a success page
        header("Location: login.php");
        exit();
    } else {
        // Redirect to an error page
        header("Location: register.php");
        exit();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
