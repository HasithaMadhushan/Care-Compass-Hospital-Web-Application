<?php
include 'config.php';

// Replace with actual patient ID from session or login
$patient_id = 1;

$sql = "SELECT record_name, record_file_path FROM medical_records WHERE patient_id = $patient_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='record-item'>";
        echo "<p><strong>Record:</strong> " . $row['record_name'] . "</p>";
        echo "<a href='" . $row['record_file_path'] . "' download>Download</a>";
        echo "</div>";
    }
} else {
    echo "<p>No medical records available.</p>";
}

$conn->close();
?>
