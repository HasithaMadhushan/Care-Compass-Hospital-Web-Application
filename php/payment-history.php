<?php
session_start();
require_once('config.php');

// Ensure the user is logged in and is a patient
if (!isset($_SESSION['patient_id'])) {
    die('Unauthorized access.');
}

$patient_id = intval($_SESSION['patient_id']);

// Fetch patient payment history along with patient name
$query = "SELECT payments.*, CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name 
          FROM payments 
          JOIN patients ON payments.patient_id = patients.id 
          WHERE payments.patient_id = ? 
          ORDER BY payments.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo 'No payment history found.';
    exit();
}

// Fetch the patient name (same for all payments as it's tied to the logged-in patient)
$patient_data = $result->fetch_assoc();
$patient_name = $patient_data['patient_name'] ?? 'Unknown Patient'; // Use a default value if null
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Payment History</h1>
    <p>Welcome, <?php echo htmlspecialchars($patient_name); ?></p>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $payment) {
                // Assign values with default fallback for missing data
                $payment_id = htmlspecialchars($payment['id'] ?? 'N/A');
                $amount = number_format($payment['amount'] ?? 0, 2);
                $method = htmlspecialchars($payment['payment_method'] ?? 'Unknown');
                $status = ucfirst(htmlspecialchars($payment['payment_status'] ?? 'Pending'));
                $date = htmlspecialchars($payment['created_at'] ?? 'Unknown');
                $encoded_patient_name = urlencode($patient_name ?? 'Unknown Patient');

                echo '<tr>';
                echo "<td>{$payment_id}</td>";
                echo "<td>Rs:{$amount}</td>";
                echo "<td>{$method}</td>";
                echo "<td>{$status}</td>";
                echo "<td>{$date}</td>";
                echo '<td>';
                echo "<a href=\"generate-receipt.php?payment_id={$payment_id}&patient_name={$encoded_patient_name}\">Download Receipt</a>";
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>
