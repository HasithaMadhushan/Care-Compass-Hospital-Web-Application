<?php
session_start();
include 'config.php'; // Include database connection

// Check if a doctor_id is passed in the query string
if (!isset($_GET['doctor_id'])) {
    die("No doctor selected.");
}

$doctor_id = intval($_GET['doctor_id']);

// Fetch doctor details
$doctor = $conn->query("SELECT * FROM doctors WHERE id = $doctor_id");
if (!$doctor || $doctor->num_rows == 0) {
    die("Doctor not found.");
}
$doctor = $doctor->fetch_assoc();

// Define the price (can be dynamic based on doctor or service)
$price = 1000; // Example static price (LKR)

// Handle appointment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    if (!isset($_SESSION['patient_id'])) {
        header('Location: login.php');
        exit();
    }

    $patient_id = $_SESSION['patient_id'];
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);

    // Check for overlapping appointments using doctor_id
    $checkQuery = "SELECT * FROM appointments WHERE doctor_id = {$doctor['id']} AND date = '$date' AND time = '$time'";
    $existing = $conn->query($checkQuery);

    if ($existing->num_rows > 0) {
        $error_message = "The selected time slot is already booked. Please choose a different time.";
    } else {
        // Simulate a successful payment process
        $payment_status = "Paid";  // Assuming payment is successful

        // Insert the appointment into the database with doctor_name and payment_status
        $sql = "INSERT INTO appointments (patient_id, doctor_id, date, time, status, payment_status, doctor_name) 
                VALUES ('$patient_id', '{$doctor['id']}', '$date', '$time', 'Scheduled', '$payment_status', '{$doctor['name']}')";

        if ($conn->query($sql)) {
            // Generate the PDF receipt
            generatePDFReceipt($patient_id, $doctor['name'], $doctor['branch'], $date, $time, $price);
            $success_message = "Your appointment has been booked successfully! You can download your receipt below.";
        } else {
            $error_message = "Error booking appointment: " . $conn->error;
        }
    }
}

// Function to generate the PDF receipt
function generatePDFReceipt($patient_id, $doctor_name, $branch, $date, $time, $price) {
    require('fpdf.php');  // Include FPDF library

    // Fetch patient details
    global $conn;
    $patient_result = $conn->query("SELECT * FROM users WHERE id = $patient_id");
    $patient = $patient_result->fetch_assoc();
    $patient_full_name = $patient['first_name'] . ' ' . $patient['last_name'];

    // Initialize FPDF instance
    $pdf = new FPDF();
    $pdf->AddPage();  // Add a page to the PDF document

    // Set title font and color
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(255, 0, 0);  // Red for logo background
    $pdf->Rect(10, 10, 50, 40, 'F'); // Red box for logo area
    $pdf->Image('../assets/logo.jpeg', 10, 10, 50);

    // Title section
    $pdf->SetXY(100, 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'RECEIPT', 0, 1, 'C');

    // Date and receipt number
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetXY(150, 25);
    $pdf->Cell(40, 10, 'Date: ' . date('Y-m-d'));
    $pdf->SetXY(150, 35);
    $pdf->Cell(40, 10, 'Receipt No: 001');

    // Patient details
    $pdf->SetXY(10, 55);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Patient Name: ' . $patient_full_name, 0, 1);
    $pdf->Cell(0, 10, 'Doctor: ' . $doctor_name, 0, 1);
    $pdf->Cell(0, 10, 'Branch: ' . $branch, 0, 1);
    $pdf->Cell(0, 10, 'Appointment Date: ' . $date, 0, 1);
    $pdf->Cell(0, 10, 'Appointment Time: ' . $time, 0, 1);

    // Service Charges section
    $pdf->SetXY(10, 100);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Ln(10); 
    $pdf->Cell(30, 10, 'CODE', 1, 0, 'C');
    $pdf->Cell(70, 10, 'Description of Service', 1, 0, 'C');
    $pdf->Cell(40, 10, 'RATE / CHARGE', 1, 0, 'C');
    $pdf->Cell(40, 10, 'LINE TOTAL', 1, 1, 'C');

    // Service data (dummy data here, replace with dynamic data)
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(30, 10, '001', 1, 0, 'C');
    $pdf->Cell(70, 10, 'Consultation', 1, 0, 'C');
    $pdf->Cell(40, 10, $price . ' LKR', 1, 0, 'C');
    $pdf->Cell(40, 10, $price . ' LKR', 1, 1, 'C');

    // Subtotal, Discount, Total and Balance
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 10, 'Subtotal', 0, 0, 'R');
    $pdf->Cell(40, 10, $price . ' LKR', 0, 1, 'C');
    $pdf->Cell(130, 10, 'Discount', 0, 0, 'R');
    $pdf->Cell(40, 10, '0.00 LKR', 0, 1, 'C');
    $pdf->Cell(130, 10, 'Tax', 0, 0, 'R');
    $pdf->Cell(40, 10, '0.00 LKR', 0, 1, 'C');
    $pdf->Cell(130, 10, 'Total', 0, 0, 'R');
    $pdf->Cell(40, 10, $price . ' LKR', 0, 1, 'C');

    // Balance Due (if any)
    $pdf->Cell(130, 10, 'Balance Paid', 0, 0, 'R');
    $pdf->Cell(40, 10, $price . ' LKR', 0, 1, 'C');

    // Payment Status
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, 'Payment Status: ' . 'Paid', 0, 1, 'C');

    // Thank you message
    $pdf->SetFont('Arial', 'I', 14);
    $pdf->Cell(0, 20, 'Thank you for choosing us!', 0, 1, 'C');

    // Add line at bottom
    $pdf->Line(10, 265, 200, 265);

    // Output the PDF to a file
    $pdf->Output('F', 'receipt.pdf');  // Save the PDF as 'receipt.pdf' on the server
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #005cbf;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004494;
        }
        .feedback-message {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: green;
        }
        .feedback-message a {
            color: #005cbf;
            text-decoration: none;
            font-weight: bold;
        }
        .feedback-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (!isset($success_message)): ?>
        <h1>Book Appointment with Dr. <?php echo $doctor['name']; ?></h1>

        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>

            <h3>Price: 1000 LKR</h3>

            <button type="submit" name="book">Book Appointment</button>
        </form>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="feedback-message">
            <p><?php echo $success_message; ?></p>
            <a href="receipt.pdf" download>Download Receipt</a><br><br>
            <a href="../index.php" class="back-btn">Back to Home</a>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="feedback-message" style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
</body>
</html>
