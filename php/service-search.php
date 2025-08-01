<?php
include 'config.php';

$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$sql = "SELECT * FROM services WHERE name LIKE '%$keyword%' OR description LIKE '%$keyword%'";

if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='service'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No services found matching your criteria.</p>";
}

$conn->close();
?>
