<?php
session_start();
include 'config.php'; // Include your database connection

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Check if an appointment ID is provided in the query string
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Fetch the appointment details to ensure it belongs to the logged-in patient
    $query = "SELECT * FROM appointments WHERE id = ? AND patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the appointment exists and belongs to the patient, cancel it
    if ($result->num_rows > 0) {
        // Update the status to 'cancelled'
        $update_query = "UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $appointment_id, $patient_id);
        if ($update_stmt->execute()) {
            header("Location: patient-dashboard.php"); // Redirect back to the dashboard
            exit();
        } else {
            echo "Error cancelling the appointment. Please try again.";
        }
    } else {
        echo "Appointment not found or you do not have permission to cancel it.";
    }
} else {
    echo "No appointment ID provided.";
}
?>
