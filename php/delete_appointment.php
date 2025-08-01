<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Delete the appointment
    $deleteQuery = "DELETE FROM appointments WHERE id = $appointment_id";
    if ($conn->query($deleteQuery)) {
        echo "<p>Appointment deleted successfully!</p>";
        header("Location: appointments-admin.php"); // Redirect back to appointments page
        exit();
    } else {
        echo "<p>Error deleting appointment: " . $conn->error . "</p>";
    }
} else {
    die("Invalid request.");
}
?>
