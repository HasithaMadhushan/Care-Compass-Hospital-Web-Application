<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

// Get the appointment ID from the query string
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Fetch appointment details
    $query = "SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, 
                     d.name as doctor_name 
              FROM appointments a
              LEFT JOIN users p ON a.patient_id = p.id
              LEFT JOIN doctors d ON a.doctor_id = d.id
              WHERE a.id = $appointment_id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
    } else {
        die("Appointment not found.");
    }
}

// Handle form submission to update the appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];
    $new_date = $_POST['date'];
    $new_time = $_POST['time'];

    // Update the appointment in the database
    $update_query = "UPDATE appointments SET 
                     status = '$status',
                     payment_status = '$payment_status',
                     date = '$new_date',
                     time = '$new_time'
                     WHERE id = $appointment_id";
    if ($conn->query($update_query)) {
        echo "<p>Appointment updated successfully!</p>";
        header("Location: appointments-admin.php"); // Redirect back to appointments page
        exit();
    } else {
        echo "<p>Error updating appointment: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #005cbf;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004494;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <h1>Update Appointment</h1>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="patient_name">Patient Name</label>
        <input type="text" id="patient_name" value="<?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>" readonly>

        <label for="doctor_name">Doctor</label>
        <input type="text" id="doctor_name" value="<?php echo htmlspecialchars($appointment['doctor_name']); ?>" readonly>

        <label for="status">Appointment Status</label>
        <select name="status" id="status">
            <option value="scheduled" <?php echo ($appointment['status'] == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
            <option value="completed" <?php echo ($appointment['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="cancelled" <?php echo ($appointment['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
        </select>

        <label for="payment_status">Payment Status</label>
        <select name="payment_status" id="payment_status">
            <option value="paid" <?php echo ($appointment['payment_status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
            <option value="unpaid" <?php echo ($appointment['payment_status'] == 'unpaid') ? 'selected' : ''; ?>>Unpaid</option>
        </select>

        <label for="date">Appointment Date</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($appointment['date']); ?>" required>

        <label for="time">Appointment Time</label>
        <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($appointment['time']); ?>" required>

        <button type="submit">Update Appointment</button>
    </form>

    <a href="appointments-admin.php" class="back-btn">Back to Appointment List</a>

</body>
</html>
