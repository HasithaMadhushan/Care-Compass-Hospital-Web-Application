<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Handle rescheduling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $new_date = $_POST['new_date'] ?? null;
    $new_time = $_POST['new_time'] ?? null;

    if ($appointment_id && $new_date && $new_time) {
        // Validate date and time format
        $new_date = date('Y-m-d', strtotime($new_date));
        $new_time = date('H:i:s', strtotime($new_time));

        // Use prepared statements for security
        $stmt = $conn->prepare("UPDATE appointments SET date = ?, time = ?, status = 'rescheduled', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);

        if ($stmt->execute()) {
            $success_message = "Appointment rescheduled successfully.";
        } else {
            $error_message = "Error rescheduling appointment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}

// Fetch scheduled appointments
$query = "
    SELECT 
        appointments.id, 
        appointments.date, 
        appointments.time, 
        appointments.status, 
        COALESCE(patients.name, 'Unknown') AS patient_name, 
        COALESCE(doctors.name, 'Unassigned') AS doctor_name
    FROM appointments
    LEFT JOIN patients ON appointments.patient_id = patients.id
    LEFT JOIN doctors ON appointments.doctor_id = doctors.id
    WHERE appointments.status IN ('scheduled', 'rescheduled')
    ORDER BY appointments.date ASC, appointments.time ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #005cbf;
            color: white;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-container input, .form-container select, .form-container button {
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        .form-container button {
            background-color: #005cbf;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #004494;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Reschedule Appointments</h1>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($appointment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                        <td><?php echo ucfirst($appointment['status']); ?></td>
                        <td>
                            <form method="POST" class="form-container">
                                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['id']); ?>">
                                <label for="new_date_<?php echo $appointment['id']; ?>">New Date:</label>
                                <input type="date" id="new_date_<?php echo $appointment['id']; ?>" name="new_date" required>
                                <label for="new_time_<?php echo $appointment['id']; ?>">New Time:</label>
                                <input type="time" id="new_time_<?php echo $appointment['id']; ?>" name="new_time" required>
                                <button type="submit">Reschedule</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No appointments available for rescheduling.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
