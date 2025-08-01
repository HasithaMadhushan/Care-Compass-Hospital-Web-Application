<?php
session_start();

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Fetch today's date
$today_date = date('Y-m-d');

// Query to fetch today's appointments
$query = "
    SELECT 
        appointments.id, 
        appointments.date, 
        appointments.time, 
        appointments.status, 
        appointments.priority, 
        patients.name AS patient_name, 
        doctors.name AS doctor_name 
    FROM appointments
    LEFT JOIN patients ON appointments.patient_id = patients.id
    LEFT JOIN doctors ON appointments.doctor_id = doctors.id
    WHERE appointments.date = '$today_date'
    ORDER BY appointments.time ASC
";

$result = $conn->query($query);

// Check for SQL errors
if (!$result) {
    die("Error fetching appointments: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Appointments</title>
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
        .priority-high {
            color: red;
            font-weight: bold;
        }
        .priority-medium {
            color: orange;
            font-weight: bold;
        }
        .priority-low {
            color: green;
            font-weight: bold;
        }
        .status-scheduled {
            color: blue;
            font-weight: bold;
        }
        .status-completed {
            color: green;
            font-weight: bold;
        }
        .status-cancelled {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Today's Appointments</h1>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Priority</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($appointment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date('H:i', strtotime($appointment['time']))); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name'] ?? 'Unassigned'); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name'] ?? 'Unknown Patient'); ?></td>
                        <td class="priority-<?php echo strtolower($appointment['priority']); ?>">
                            <?php echo ucfirst($appointment['priority']); ?>
                        </td>
                        <td class="status-<?php echo strtolower($appointment['status']); ?>">
                            <?php echo ucfirst($appointment['status']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No appointments scheduled for today.</td>
                </tr>
                <form method="POST" action="update-appointment-status.php">
    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
    <select name="status">
        <option value="scheduled" <?php if ($appointment['status'] === 'scheduled') echo 'selected'; ?>>Scheduled</option>
        <option value="completed" <?php if ($appointment['status'] === 'completed') echo 'selected'; ?>>Completed</option>
        <option value="cancelled" <?php if ($appointment['status'] === 'cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>
    <button type="submit">Update Status</button>
</form>

            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
