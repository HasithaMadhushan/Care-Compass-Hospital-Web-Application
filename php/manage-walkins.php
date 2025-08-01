<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Handle new walk-in patient submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_walkin') {
        $patient_name = $conn->real_escape_string($_POST['patient_name']);
        $contact_details = $conn->real_escape_string($_POST['contact_details']);
        $reason = $conn->real_escape_string($_POST['reason']);
        $priority = $conn->real_escape_string($_POST['priority']);
        $status = 'waiting'; // Default status for new walk-ins

        $query = "INSERT INTO walkins (patient_name, contact_details, reason, priority, status) 
                  VALUES ('$patient_name', '$contact_details', '$reason', '$priority', '$status')";
        if ($conn->query($query)) {
            $success_message = "Walk-in patient added successfully.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Fetch available doctors
$doctors_query = "SELECT id, name, specialization FROM doctors WHERE status = 'active'";
$doctors = $conn->query($doctors_query);

// Fetch walk-in patients
$walkins_query = "SELECT * FROM walkins ORDER BY priority DESC, created_at ASC";
$walkins = $conn->query($walkins_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Walk-ins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        .form-container, .table-container {
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input, .form-container select, .form-container textarea {
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        .form-container button {
            background-color: #005cbf;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #004494;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .status-waiting {
            color: orange;
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
    <h1>Manage Walk-in Patients</h1>

    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Add New Walk-in Patient -->
    <div class="form-container">
        <h2>Add New Walk-in Patient</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_walkin">
            <label for="patient_name">Patient Name:</label>
            <input type="text" id="patient_name" name="patient_name" required>

            <label for="contact_details">Contact Details:</label>
            <input type="text" id="contact_details" name="contact_details" required>

            <label for="reason">Reason for Visit:</label>
            <textarea id="reason" name="reason" rows="3" required></textarea>

            <label for="priority">Priority:</label>
            <select id="priority" name="priority" required>
                <option value="High">High (Emergency)</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>

            <button type="submit">Add Walk-in</button>
        </form>
    </div>

    <!-- Walk-in Patients List -->
    <div class="table-container">
        <h2>Walk-in Patients</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Contact Details</th>
                    <th>Reason</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Estimated Wait Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($walkins->num_rows > 0): ?>
                    <?php 
                    $estimated_wait_time = 0; // Initialize wait time
                    while ($walkin = $walkins->fetch_assoc()): 
                        $estimated_wait_time += 15; // Assume 15 minutes per patient
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($walkin['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($walkin['contact_details']); ?></td>
                            <td><?php echo htmlspecialchars($walkin['reason']); ?></td>
                            <td class="priority-<?php echo strtolower($walkin['priority']); ?>">
                                <?php echo ucfirst($walkin['priority']); ?>
                            </td>
                            <td class="status-<?php echo htmlspecialchars($walkin['status']); ?>">
                                <?php echo ucfirst($walkin['status']); ?>
                            </td>
                            <td>
                                <?php echo $walkin['status'] === 'waiting' ? $estimated_wait_time . ' minutes' : 'N/A'; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No walk-in patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
