<?php
session_start();

// Check if user is logged in and has the staff role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Ensure staff ID is set in the session
if (!isset($_SESSION['user_id'])) {
    die("Error: Staff ID is not set. Please log in again.");
}

// Fetch tasks for the logged-in staff member
$staff_id = intval($_SESSION['user_id']);
$query = "SELECT * FROM tasks WHERE staff_id = $staff_id ORDER BY deadline ASC";
$result = $conn->query($query);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = intval($_POST['task_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Validate the status value
    $valid_statuses = ['pending', 'in-progress', 'completed'];
    if (in_array($status, $valid_statuses)) {
        $update_query = "UPDATE tasks SET status = '$status', updated_at = NOW() WHERE id = $task_id AND staff_id = $staff_id";
        if ($conn->query($update_query)) {
            $message = "Task status updated successfully.";
        } else {
            $message = "Error updating task status: " . $conn->error;
        }
    } else {
        $message = "Invalid status value.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task Status</title>
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
        .status-pending {
            color: red;
            font-weight: bold;
        }
        .status-in-progress {
            color: orange;
            font-weight: bold;
        }
        .status-completed {
            color: green;
            font-weight: bold;
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
    <h1>Update Task Status</h1>
    
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
                <th>Description</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($task = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['id']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($task['deadline']))); ?></td>
                        <td class="status-<?php echo htmlspecialchars($task['status']); ?>">
                            <?php echo ucfirst($task['status']); ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status" required>
                                    <option value="pending" <?php if ($task['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="in-progress" <?php if ($task['status'] === 'in-progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="completed" <?php if ($task['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No tasks assigned to you at the moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
