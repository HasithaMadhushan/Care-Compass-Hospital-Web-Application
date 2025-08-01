<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';

// Get today's date
$today = date('Y-m-d');

// Fetch appointments scheduled for tomorrow
$appointments = $conn->query("SELECT appointments.*, 
                                     patients.name AS patient_name, 
                                     patients.email AS patient_email 
                              FROM appointments 
                              JOIN patients ON appointments.patient_id = patients.id 
                              WHERE DATE(date) = DATE_ADD('$today', INTERVAL 1 DAY)");

// Function to send email reminder
function sendReminder($email, $subject, $message) {
    // Use PHP mail function or integrate with SMTP (e.g., PHPMailer)
    if (mail($email, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}

// Handle sending email reminders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_reminder'])) {
    $email = $_POST['email'];
    $patient_name = $_POST['patient_name'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $subject = "Appointment Reminder";
    $message = "Dear $patient_name,\n\n"
             . "This is a friendly reminder about your appointment scheduled for $appointment_date at $appointment_time.\n\n"
             . "Please let us know if you have any questions or need to reschedule.\n\n"
             . "Best regards,\n"
             . "Care Compass Hospitals";

    if (sendReminder($email, $subject, $message)) {
        echo "<p style='color: green;'>Reminder sent successfully to $email.</p>";
    } else {
        echo "<p style='color: red;'>Failed to send reminder to $email.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Reminders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
            background-color: #f4f4f4;
        }
        .highlight {
            background-color: #ffffcc;
        }
    </style>
</head>
<body>
    <h1>Appointment Reminders</h1>
    <table>
        <thead>
            <tr>
                <th>Patient</th>
                <th>Email</th>
                <th>Appointment Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($appointments->num_rows > 0): ?>
                <?php while ($appointment = $appointments->fetch_assoc()): ?>
                    <tr class="<?php echo ($appointment['special_preparation'] == 1) ? 'highlight' : ''; ?>">
                        <td><?php echo $appointment['patient_name']; ?></td>
                        <td><?php echo $appointment['patient_email']; ?></td>
                        <td><?php echo $appointment['date']; ?></td>
                        <td><?php echo $appointment['time']; ?></td>
                        <td><?php echo ucfirst($appointment['status']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="email" value="<?php echo $appointment['patient_email']; ?>">
                                <input type="hidden" name="patient_name" value="<?php echo $appointment['patient_name']; ?>">
                                <input type="hidden" name="appointment_date" value="<?php echo $appointment['date']; ?>">
                                <input type="hidden" name="appointment_time" value="<?php echo $appointment['time']; ?>">
                                <button type="submit" name="send_reminder">Send Reminder</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No reminders for tomorrow.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
