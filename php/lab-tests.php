<?php
include 'config.php';

$query = "SELECT * FROM lab_services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Tests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Laboratory & Tests</h1>
    <div class="lab-tests">
        <?php while ($test = $result->fetch_assoc()): ?>
            <div class="lab-card">
                <h2><?php echo $test['service_name']; ?></h2>
                <p><?php echo $test['details']; ?></p>
                <p><strong>Price:</strong> $<?php echo $test['price']; ?></p>
                <a href="book-test.php?test_id=<?php echo $test['id']; ?>" class="btn">Book a Test</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
