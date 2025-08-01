<?php
require_once('fpdf.php');
require_once('config.php');

// Suppress output
ob_start();

if (!isset($_GET['payment_id']) || empty($_GET['payment_id']) || !isset($_GET['patient_name'])) {
    die('Payment ID and Patient Name are required.');
}

$payment_id = intval($_GET['payment_id']);
$patient_name = htmlspecialchars($_GET['patient_name']); // Passed from payment-history.php

// Fetch payment details
$query = "SELECT * FROM payments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Payment record not found.');
}

$payment = $result->fetch_assoc();
$stmt->close();

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Payment Receipt', 0, 1, 'C');

// Payment Details
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Receipt No: ' . htmlspecialchars($payment['id']), 0, 1);
$pdf->Cell(0, 10, 'Patient Name: ' . $patient_name, 0, 1);
$pdf->Cell(0, 10, 'Amount:Rs:'. number_format($payment['amount'], 2), 0, 1);
$pdf->Cell(0, 10, 'Payment Method: ' . htmlspecialchars($payment['payment_method']), 0, 1);
$pdf->Cell(0, 10, 'Status: ' . ucfirst($payment['payment_status']), 0, 1);
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d H:i:s', strtotime($payment['created_at'])), 0, 1);

// Clear output buffer before generating the PDF
ob_clean();

// Output PDF
$pdf->Output('I', 'payment_receipt.pdf');
