<?php
include 'config.php';

if (isset($_GET['keyword'])) {
    $keyword = $conn->real_escape_string($_GET['keyword']);
    $sql = "SELECT * FROM services WHERE name LIKE '%$keyword%' OR description LIKE '%$keyword%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['name'] . " - " . $row['description'] . "</p>";
        }
    } else {
        echo "No results found.";
    }

    $conn->close();
}
?>
