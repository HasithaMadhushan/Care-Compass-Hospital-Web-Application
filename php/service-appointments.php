<?php
session_start();
include 'config.php';

// Schedule a new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = !empty($_POST['doctor_id']) ? intval($_POST['doctor_id']) : null; // Allow unassigned
    $date = $_POST['date'];
    $time = $_POST['time'];
    $priority = $_POST['priority'];
    $special_preparation = isset($_POST['special_preparation']) ? 1 : 0; // Checkbox for special preparation

    // Validate input
    if (!empty($patient_id) && !empty($date) && !empty($time) && in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
        $query = "INSERT INTO appointments (patient_id, doctor_id, date, time, status, priority, special_preparation)
                  VALUES ('$patient_id', " . ($doctor_id ? "'$doctor_id'" : "NULL") . ", '$date', '$time', 'scheduled', '$priority', '$special_preparation')";

        if ($conn->query($query)) {
            $success_message = "Appointment scheduled successfully!";
        } else {
            $error_message = "Error scheduling appointment: " . $conn->error;
        }
    } else {
        $error_message = "All fields (except doctor) are required and priority must be valid.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Schedule an Appointment</h1>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="patient_id">Patient ID:</label>
        <input type="text" id="patient_id" name="patient_id" required>

        <label for="doctor_id">Doctor ID (Optional):</label>
        <input type="text" id="doctor_id" name="doctor_id">

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>

        <label for="priority">Priority:</label>
        <select id="priority" name="priority" required>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
        </select>

        <label for="special_preparation">
            <input type="checkbox" id="special_preparation" name="special_preparation"> Special Preparation Required
        </label>

        <button type="submit" class="btn">Book Appointment</button>
    </form>
</body>
</html>
