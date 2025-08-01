<?php
include 'config.php';

// Fetch search parameters
$name = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';
$specialization = isset($_GET['specialization']) ? $conn->real_escape_string($_GET['specialization']) : '';

// Build SQL query
$sql = "SELECT * FROM doctors WHERE 1=1";

if (!empty($name)) {
    $sql .= " AND name LIKE '%$name%'";
}

if (!empty($specialization)) {
    $sql .= " AND specialization LIKE '%$specialization%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='doctor-profile'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p><strong>Specialization:</strong> " . $row['specialization'] . "</p>";
        echo "<p><strong>Qualifications:</strong> " . $row['qualifications'] . "</p>";
        echo "<p><strong>Contact:</strong> " . $row['contact_details'] . "</p>";
        echo "<p><strong>Availability:</strong> " . $row['availability'] . "</p>";
        echo "<p><strong>Branch:</strong> " . $row['branch'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No doctors found matching your criteria.</p>";
}

$conn->close();
?>
