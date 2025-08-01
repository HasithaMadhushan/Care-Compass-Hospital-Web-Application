<?php
include 'config.php';

if (!isset($_GET['service_id'])) {
    die("Invalid service ID.");
}

$service_id = intval($_GET['service_id']);
$result = $conn->query("SELECT * FROM services WHERE id = $service_id");

if ($result->num_rows === 0) {
    die("Service not found.");
}

$service = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_SESSION['patient_id']; // Assuming user is logged in
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);

    $query = "INSERT INTO appointments (patient_id, service_id, date, time) VALUES ($patient_id, $service_id, '$date', '$time')";
    if ($conn->query($query)) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Book Service</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="services-dashboard.php">Services</a>
            <a href="patient-dashboard.php">Patient Dashboard</a>
            <a href="contact-us.php">Contact Us</a>
        </nav>
    </header>

    <main>
        <h2>Book <?php echo $service['name']; ?></h2>
        <p><?php echo $service['description']; ?></p>
        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
            <button type="submit">Confirm Booking</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Care Compass Hospitals. All rights reserved.</p>
    </footer>
</body>
</html>
