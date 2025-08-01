<?php
include 'config.php';

$sql = "SELECT name, message FROM testimonials ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);

$testimonials = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($testimonials);
?>
