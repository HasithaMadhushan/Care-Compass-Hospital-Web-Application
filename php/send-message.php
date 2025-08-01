<?php
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Include your database connection file (config.php)
    include 'config.php'; // Make sure this file contains the database connection logic

    // Prepare and bind the SQL query
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query and check if successful
    if ($stmt->execute()) {
        // Success: Show success message
        $_SESSION['feedback'] = "Thank you for your message! We will get back to you shortly.";
        header("Location: contact-us.php"); // Redirect back to contact page
        exit();
    } else {
        // Error: Show error message
        $_SESSION['feedback'] = "Oops! Something went wrong. Please try again later.";
        header("Location: contact-us.php"); // Redirect back to contact page
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
