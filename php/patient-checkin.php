<?php
session_start();

// Check if user is logged in and has the staff role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Check for form submission to check-in a patient
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = 'active'; // New patients are always active upon check-in

    // Check if patient already exists
    $check_query = "SELECT * FROM patients WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $message = "Patient with email $email is already checked in.";
    } else {
        // Insert new patient record
        $insert_query = "INSERT INTO patients (name, email, phone, status, created_at) VALUES 
            ('$name', '$email', '$phone', '$status', NOW())";

        if ($conn->query($insert_query)) {
            $message = "Patient checked in successfully.";
        } else {
            $message = "Error checking in patient: " . $conn->error;
        }
    }
}

// Fetch all patients
$query = "SELECT * FROM patients ORDER BY created_at DESC";
$result = $conn->query($query);

// Check for SQL errors
if (!$result) {
    die("Error fetching patient records: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patient Check-ins</title>
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
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            padding: 8px;
            margin: 5px 0;
            width: 95%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #005cbf;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
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
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Manage Patient Check-ins</h1>

    <!-- Display success/error message -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo isset($error) && $error ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Check-in form -->
    <form method="POST">
        <h2>Check-in New Patient</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>

        <button type="submit">Check-in</button>
    </form>

    <!-- Patient list -->
    <h2>All Checked-in Patients</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Check-in Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($patient = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['id']); ?></td>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                        <td><?php echo htmlspecialchars($patient['email']); ?></td>
                        <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($patient['status'])); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($patient['created_at']))); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No patients checked in yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
