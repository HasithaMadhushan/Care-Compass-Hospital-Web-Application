<?php
include 'config.php';

// Replace with actual patient ID from session or login
$patient_id = 1;

$sql = "SELECT doctor_name, date, time, status FROM appointments WHERE patient_id = $patient_id ORDER BY date, time";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='appointment-item'>";
        echo "<p><strong>Doctor:</strong> " . $row['doctor_name'] . "</p>";
        echo "<p><strong>Date:</strong> " . $row['date'] . "</p>";
        echo "<p><strong>Time:</strong> " . $row['time'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No upcoming appointments.</p>";
}

$conn->close();
?>
