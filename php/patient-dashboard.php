<?php
session_start();
include 'config.php'; // Include database connection

// Check if the user is logged in (patient_id should be in the session)
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php"); // Redirect to login page if patient is not logged in
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Process form submission for updating patient details and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get the form inputs
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update patient details in the 'users' table
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'patient'";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $patient_id);

    if ($stmt->execute()) {
        $success_message = "Your information has been updated successfully.";
    } else {
        $error_message = "An error occurred while updating your information.";
    }

    // Check if password change is requested
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the database
        $password_query = "UPDATE users SET password = ? WHERE id = ? AND role = 'patient'";
        $password_stmt = $conn->prepare($password_query);
        $password_stmt->bind_param("si", $hashed_password, $patient_id);

        if ($password_stmt->execute()) {
            $password_success_message = "Your password has been updated successfully.";
        } else {
            $password_error_message = "An error occurred while changing your password.";
        }
    }
}

// Fetch patient details from the 'users' table
$patient_query = "SELECT * FROM users WHERE id = ? AND role = 'patient'";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();

// Fetch appointments for the logged-in patient
$appointments_query = "SELECT * FROM appointments WHERE patient_id = ?";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$appointments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    
    <style>
        /* Styles for the page */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #005cbf;
            text-align: center;
        }
        .message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .dashboard-section {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        td {
            background-color: #ffffff;
        }

        /* Status badge styling */
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }

        .status-scheduled {
            background-color: #ffcc00;
        }

        .status-completed {
            background-color: #28a745;
        }

        .status-cancelled {
            background-color: #dc3545;
        }

        .status-unassigned {
            background-color: #6c757d;
        }

        .actions a {
            padding: 5px 15px;
            background-color: #005cbf;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .actions a:hover {
            background-color: #004494;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        /* Button Styles */
.actions a {
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    margin-right: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
}

/* Cancel Button Style */
.btn-cancel {
    background-color: #f44336; /* Red */
    color: white;
    font-weight: bold;
    border: 2px solid #f44336; /* Matching border */
}

.btn-cancel:hover {
    background-color: #d32f2f; /* Darker red on hover */
    border-color: #d32f2f; /* Matching border color */
    transform: scale(1.05); /* Slightly enlarge on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
}

.btn-cancel i {
    margin-right: 8px;
    font-size: 18px;
}

/* Download Report Button Style */
.btn-download {
    background-color: #4caf50; /* Green */
    color: white;
    font-weight: bold;
    border: 2px solid #4caf50; /* Matching border */
}

.btn-download:hover {
    background-color: #388e3c; /* Darker green on hover */
    border-color: #388e3c; /* Matching border color */
    transform: scale(1.05); /* Slightly enlarge on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
}

.btn-download i {
    margin-right: 8px;
    font-size: 18px;
}

/* Button Styles for Disabled State (if needed) */
.btn-cancel:disabled, .btn-download:disabled {
    background-color: #b0bec5;
    color: #ffffff;
    border-color: #b0bec5;
    cursor: not-allowed;
    box-shadow: none;
}


        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome <?php echo htmlspecialchars($patient['first_name']); ?>!</h1>

        <!-- Display the success message at the bottom -->
        <?php if (isset($success_message)): ?>
            <div class="message success" id="success-message"><i class="fas fa-check-circle"></i><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="dashboard-section">
            <h2>Your Appointments</h2>
            <?php if ($appointments_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
            <td><?php echo htmlspecialchars($appointment['doctor_name']); // Doctor's name from the appointments table ?></td>
            <td>
                <span class="status status-<?php echo strtolower($appointment['status']); ?>">
                    <?php echo ucfirst($appointment['status']); ?>
                </span>
            </td>
            <td>
    <?php if ($appointment['status'] === 'scheduled'): ?>
        <a href="cancel_appointment.php?appointment_id=<?php echo $appointment['id']; ?>" class="btn-cancel">
            <i class="fas fa-times-circle"></i> Cancel
        </a>
    <?php elseif ($appointment['status'] === 'completed'): ?>
        <!-- Add link to download the fake patient report PDF for completed appointments -->
        <a href="generate_report.php?appointment_id=<?php echo $appointment['id']; ?>" class="btn-download">
            <i class="fas fa-download"></i> Download Report
        </a>
    <?php endif; ?>
</td>
        </tr>
    <?php endwhile; ?>
</tbody>
                </table>
            <?php else: ?>
                <p>No upcoming appointments found. Please book one soon!</p>
            <?php endif; ?>
        </div>

        <div class="dashboard-section">
            <h2>Update Your Information</h2>

            <form method="POST" action="patient-dashboard.php">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>

                <h3>Change Password</h3>
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter new password">

                <button type="submit" class="btn-update">Update Information</button>
            </form>
        </div>

        <section class="back-home">
            <a href="../index.php" class="back-btn">Back to Home</a>
        </section>
    </div>

    <script>
        // Set a timeout for hiding the success message after 2 seconds
        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 2000);
    </script>
</body>
</html>

<?php
// Helper function to get doctor name
function get_doctor_name($doctor_id, $conn) {
    $query = "SELECT name FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
    return $doctor['name'];
}
?>
