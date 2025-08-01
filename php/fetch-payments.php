<?php
include 'config.php';

// Replace with actual patient ID from session or login
$patient_id = 1;

$sql = "SELECT amount, status, created_at FROM payments WHERE patient_id = $patient_id ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='payment-item'>";
        echo "<p><strong>Amount:</strong> $" . $row['amount'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "<p><strong>Date:</strong> " . $row['created_at'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No payment history available.</p>";
}

$conn->close();
?>
