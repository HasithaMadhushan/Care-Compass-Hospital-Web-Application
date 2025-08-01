<?php
session_start();

// Check if user is logged in and has the staff role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Fetch all patients
$query = "SELECT * FROM patients ORDER BY created_at DESC";
$result = $conn->query($query);

// Check for SQL errors
if (!$result) {
    die("Error fetching patient records: " . $conn->error);
}

// Update patient record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id'])) {
    $patient_id = intval($_POST['patient_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = $conn->real_escape_string($_POST['status']);

    // Validate status
    $valid_statuses = ['active', 'archived'];
    if (!in_array($status, $valid_statuses)) {
        $message = "Invalid status value.";
    } else {
        $update_query = "UPDATE patients SET 
            name = '$name',
            email = '$email',
            phone = '$phone',
            status = '$status',
            updated_at = NOW()
            WHERE id = $patient_id";

        if ($conn->query($update_query)) {
            $message = "Patient record updated successfully.";
        } else {
            $message = "Error updating patient record: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Update Patient Records</title>
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
        form {
            display: inline;
        }
        button {
            background-color: #005cbf;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
        }
        input[type="text"], select {
            padding: 5px;
            margin: 5px 0;
            width: 95%;
        }
    </style>
</head>
<body>
    <h1>View and Update Patient Records</h1>

    <!-- Display success/error message -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo isset($error) && $error ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($patient = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo htmlspecialchars($patient['id']); ?></td>
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required></td>
                            <td><input type="text" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required></td>
                            <td><input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>"></td>
                            <td>
                                <select name="status" required>
                                    <option value="active" <?php if ($patient['status'] === 'active') echo 'selected'; ?>>Active</option>
                                    <option value="archived" <?php if ($patient['status'] === 'archived') echo 'selected'; ?>>Archived</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                                <button type="submit">Update</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No patients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
