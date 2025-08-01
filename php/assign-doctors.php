<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

// Fetch only scheduled appointments
$query = "SELECT a.*, p.first_name as patient_first_name, p.last_name as patient_last_name, d.name as doctor_name, d.id as doctor_id 
          FROM appointments a
          LEFT JOIN users p ON a.patient_id = p.id
          LEFT JOIN doctors d ON a.doctor_id = d.id
          WHERE a.status = 'scheduled'"; // Only select appointments with status 'scheduled'

$result = $conn->query($query);

// If an appointment is updated (doctor is assigned/modified)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['doctor_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $doctor_id = intval($_POST['doctor_id']);

    // Update the doctor's assignment for the appointment
    $update_query = "UPDATE appointments SET doctor_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $doctor_id, $appointment_id);

    if ($stmt->execute()) {
        $admin_message = "Doctor assigned successfully.";
        $stmt->close();
    } else {
        $error_message = "Error: " . $conn->error;
        $stmt->close();
    }
}
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

        /* Feedback Message Styles */
        .feedback-message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-weight: bold;
            display: none;
            transition: opacity 0.5s ease-in-out;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .assign-button {
            padding: 10px 20px;
            background-color: #005cbf;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .assign-button:hover {
            background-color: #004494;
        }

        /* Modal styles */
        .modal {
            display: none;
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }

        .modal-content input, .modal-content select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #005cbf;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .modal-content button:hover {
            background-color: #004494;
        }

    </style>
</head>
<body>
    <h1>Assign Doctors to Patients</h1>

    <!-- Feedback Message -->
    <div id="feedback-container">
        <div id="feedback-message" class="feedback-message"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Status</th>
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
                            <span class="status status-scheduled">
                                <?php echo ucfirst($appointment['status']); ?>
                            </span>
                        </td>
                        <td class="actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                <select name="doctor_id">
                                    <option value="">Select Doctor</option>
                                    <?php 
                                    // Fetch all doctors
                                    $doctor_query = "SELECT id, name FROM doctors"; // Now referencing the 'doctors' table
                                    $doctor_result = $conn->query($doctor_query);
                                    while ($doctor = $doctor_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $doctor['id']; ?>" <?php echo $doctor['id'] == $appointment['doctor_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($doctor['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <button type="submit" class="assign-button">Assign Doctor</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No scheduled appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin-dashboard.php" class="back-btn">Back</a>

    <script>
        // Show feedback message after actions
        function showFeedbackMessage(message, type) {
            const feedbackMessage = document.getElementById('feedback-message');
            
            feedbackMessage.textContent = message;
            if (type === 'success') {
                feedbackMessage.classList.add('success-message');
                feedbackMessage.classList.remove('error-message');
            } else {
                feedbackMessage.classList.add('error-message');
                feedbackMessage.classList.remove('success-message');
            }

            feedbackMessage.style.display = 'block';
            setTimeout(() => {
                feedbackMessage.style.display = 'none';
            }, 3000);
        }

        <?php if (isset($admin_message)): ?>
            showFeedbackMessage('<?php echo $admin_message; ?>', 'success');
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            showFeedbackMessage('<?php echo $error_message; ?>', 'error');
        <?php endif; ?>
    </script>

</body>
</html>
