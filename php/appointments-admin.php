<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

// Fetch all appointments
$query = "SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, d.name as doctor_name 
          FROM appointments a
          LEFT JOIN users p ON a.patient_id = p.id
          LEFT JOIN doctors d ON a.doctor_id = d.id";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
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
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .status {
            padding: 5px;
            border-radius: 5px;
        }
        .status-scheduled {
            background-color: #ffcc00;
            color: #fff;
        }
        .status-completed {
            background-color: #28a745;
            color: #fff;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .status-unassigned {
            background-color: #6c757d;
            color: #fff;
        }
        .actions a {
            padding: 5px 10px;
            background-color: #005cbf;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        .actions a:hover {
            background-color: #004494;
        }
        /* Red color for delete button */
        .actions a.delete {
            background-color: #dc3545;
        }
        .actions a.delete:hover {
            background-color: #c82333;
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
    <h1>Manage Appointments</h1>
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($appointment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                        <td>
                            <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                                <?php echo ucfirst($appointment['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($appointment['payment_status']); ?></td>
                        <td class="actions">
                            <a href="update_appointment.php?id=<?php echo $appointment['id']; ?>">Update</a>
                            <a href="delete_appointment.php?id=<?php echo $appointment['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="admin-dashboard.php" class="back-btn">Back</a>
</body>
</html>
