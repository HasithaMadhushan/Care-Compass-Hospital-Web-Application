<?php
require_once('fpdf.php'); // Include FPDF library

// Check if appointment_id is provided
if (!isset($_GET['appointment_id'])) {
    die("Appointment ID is required.");
}

$appointment_id = $_GET['appointment_id'];

// Include database connection
include 'config.php';

// Fetch appointment details
$appointment_query = "SELECT * FROM appointments WHERE id = ?";
$stmt = $conn->prepare($appointment_query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment_result = $stmt->get_result();
$appointment = $appointment_result->fetch_assoc();

if (!$appointment) {
    die("Invalid appointment ID.");
}

// Fetch patient details for the report
$patient_query = "SELECT * FROM users WHERE id = ? AND role = 'patient'";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("i", $appointment['patient_id']);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();

if (!$patient) {
    die("Invalid patient ID.");
}

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();

// Title of the document
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(0, 0, 128); // Dark Blue for the title
$pdf->Cell(200, 10, 'Patient Report', 0, 1, 'C');

// Patient Information
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0); // Black for normal text
$pdf->Cell(100, 10, 'Patient Name: ' . $patient['first_name'] . ' ' . $patient['last_name']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Appointment Date: ' . $appointment['date']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Doctor: ' . $appointment['doctor_name']); // Doctor's name from the appointment table
$pdf->Ln(10);

// ** Random Lab Reports Section **
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(255, 255, 255); // White text for the heading
$pdf->SetFillColor(0, 102, 204); // Blue background for Lab Reports section
$pdf->Cell(200, 10, 'Lab Reports', 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Black text for lab results

// Predefined random lab results
$lab_results = [
    'Blood Test' => ['Normal', 'High cholesterol', 'Anemia', 'Vitamin D deficiency'],
    'X-ray' => ['Clear', 'Mild inflammation', 'Fracture detected'],
    'ECG' => ['Normal', 'Irregular heartbeat', 'Mild arrhythmia'],
];

// Randomly select a result from each test
$random_blood_test = $lab_results['Blood Test'][array_rand($lab_results['Blood Test'])];
$random_xray = $lab_results['X-ray'][array_rand($lab_results['X-ray'])];
$random_ecg = $lab_results['ECG'][array_rand($lab_results['ECG'])];

// Display the random lab results
$pdf->Cell(200, 10, 'Blood Test: ' . $random_blood_test);
$pdf->Ln();
$pdf->Cell(200, 10, 'X-ray: ' . $random_xray);
$pdf->Ln();
$pdf->Cell(200, 10, 'ECG: ' . $random_ecg);
$pdf->Ln(10);

// ** Random Prescription Section **
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(255, 255, 255); // White text for the heading
$pdf->SetFillColor(0, 153, 76); // Green background for Prescription section
$pdf->Cell(200, 10, 'Prescription', 0, 1, 'C', true);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Black text for prescription details

// Predefined random prescriptions
$medications = [
    'Hypertension' => [
        'Medicine A' => '1 tablet daily (for blood pressure)',
        'Medicine B' => '2 tablets daily (for heart health)',
    ],
    'Diabetes' => [
        'Medicine C' => '1 tablet before meals (for blood sugar)',
        'Medicine D' => '2 tablets daily (for insulin resistance)',
    ],
    'General' => [
        'Medicine X' => '1 tablet daily',
        'Medicine Y' => '1 tablet before meals',
    ],
];

// Randomly choose a condition (for demo purposes, we assume the condition is either Hypertension, Diabetes, or General)
$conditions = ['Hypertension', 'Diabetes', 'General'];
$random_condition = $conditions[array_rand($conditions)];

// Get medications based on the random condition
$selected_medications = $medications[$random_condition];

// Display the randomly chosen medications
foreach ($selected_medications as $medicine => $dosage) {
    $pdf->Cell(200, 10, $medicine . ': ' . $dosage);
    $pdf->Ln();
}

$pdf->Ln(10);

// Add a footer
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(128, 128, 128); // Gray text for footer
$pdf->Cell(200, 10, 'Care Compass Hospital | Contact: info@carecompass.com', 0, 1, 'C');

// Output the PDF document
$pdf->Output('patient_report_' . $appointment_id . '.pdf', 'I'); // Display in browser
?>
