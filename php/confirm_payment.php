<?php
session_start();
include 'config.php'; // Include database connection
require('fpdf.php'); // Include the FPDF library for PDF generation

// Check if the appointment_id is passed via URL
if (!isset($_GET['appointment_id']) || empty($_GET['appointment_id'])) {
    echo "Appointment ID is missing!";
    exit();
}

$appointment_id = $_GET['appointment_id']; // Get the appointment ID from the URL

// Fetch appointment details from the database
$query = "SELECT * FROM appointments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo "Appointment not found!";
    exit();
}

// Fetch patient details
$patient_id = $appointment['patient_id'];
$patient_query = "SELECT * FROM patients WHERE id = ?";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();

if (!$patient) {
    echo "Patient not found!";
    exit();
}

// Handle payment confirmation (assuming you have a form for payment)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process payment logic here (we're skipping actual payment gateway integration)
    $payment_method = $_POST['payment_method']; // Can be 'credit card', 'debit card', etc.
    $payment_amount = 1000; // Fixed amount of 1000 LKR
    $payment_status = 'success'; // Set status based on payment processing result

    // Insert payment details into the database
    $payment_query = "INSERT INTO payments (patient_id, amount, payment_method, payment_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($payment_query);
    $stmt->bind_param("idss", $patient_id, $payment_amount, $payment_method, $payment_status);
    $stmt->execute();

    // Update the appointment status
    $update_appointment_query = "UPDATE appointments SET status = 'scheduled' WHERE id = ?";
    $stmt = $conn->prepare($update_appointment_query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    // Generate PDF receipt (passing $conn and patient details explicitly)
    generate_pdf($patient, $appointment, $payment_amount, $payment_method, $payment_status, $conn);

    echo "Payment confirmed. Receipt has been generated. <a href='receipt.pdf' target='_blank'>Download your receipt</a>";
    exit();
}

// Function to generate the PDF receipt
function generate_pdf($patient, $appointment, $payment_amount, $payment_method, $payment_status, $conn) {
    // Create new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 51, 102); // Set title color to a blue shade
    $pdf->Cell(0, 10, 'Care Compass Hospitals - Payment Receipt', 0, 1, 'C');
    $pdf->Ln(10);

    // Add hospital logo
    $pdf->Ln(20); // Space after logo

    // Set patient details section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 0, 0); // Set text color to black
    $pdf->Cell(0, 10, 'Patient Information', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . $patient['first_name'] . ' ' . $patient['last_name']);
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Email: ' . $patient['email']);
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Phone: ' . $patient['phone']);
    $pdf->Ln(12);

    // Set appointment details section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Appointment Information', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Appointment Date: ' . $appointment['date']);
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Appointment Time: ' . $appointment['time']);
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Doctor: ' . get_doctor_name($appointment['doctor_id'], $conn));
    $pdf->Ln(12);

    // Set payment details section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Payment Information', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Amount Paid: LKR ' . number_format($payment_amount, 2));
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Payment Method: ' . $payment_method);
    $pdf->Ln(8);
    $pdf->Cell(0, 10, 'Payment Status: ' . $payment_status);
    $pdf->Ln(12);

    // Footer note
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Thank you for choosing Care Compass Hospitals!', 0, 1, 'C');
    $pdf->Ln(10);

    // Save the PDF file
    $pdf->Output('F', 'receipt.pdf'); // Save the PDF to the server as 'receipt.pdf'
}

// Helper function to get doctor name
function get_doctor_name($doctor_id, $conn) {
    $query = "SELECT name FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
    return $doctor['name'];
}
?>

<!-- HTML Form for Payment -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
</head>
<body>
    <h2>Confirm Payment for Your Appointment</h2>

    <p>Appointment with: <?php echo $appointment['date']; ?> at <?php echo $appointment['time']; ?></p>
    <p>Doctor: <?php echo get_doctor_name($appointment['doctor_id'], $conn); ?></p>

    <form action="confirm_payment.php?appointment_id=<?php echo $appointment_id; ?>" method="POST">
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method">
            <option value="credit_card">Credit Card</option>
            <option value="debit_card">Debit Card</option>
            <option value="online_banking">Online Banking</option>
        </select><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" value="1000" readonly><br>

        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
