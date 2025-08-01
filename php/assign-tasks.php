<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php'; // Include your database connection

// Handle task assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_task'])) {
    $staff_id = intval($_POST['staff_id']);
    $task_description = $conn->real_escape_string($_POST['task_description']);
    $deadline = $conn->real_escape_string($_POST['deadline']);

    $query = "INSERT INTO tasks (staff_id, description, deadline, status) VALUES ($staff_id, '$task_description', '$deadline', 'pending')";
    if ($conn->query($query)) {
        $message = "<p style='color: green;'>Task assigned successfully.</p>";
    } else {
        $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch staff
$staff = $conn->query("SELECT id, name FROM staff WHERE status = 'active'");

// Fetch tasks
$tasks = $conn->query("SELECT tasks.*, staff.name AS staff_name FROM tasks JOIN staff ON tasks.staff_id = staff.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tasks</title>
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
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Assign Tasks</h1>

    <!-- Success/Error Message -->
    <?php if (isset($message)) echo $message; ?>

    <!-- Task Assignment Form -->
    <form method="POST">
        <label for="staff_id">Assign To:</label>
        <select name="staff_id" id="staff_id" required>
            <option value="">Select Staff</option>
            <?php while ($row = $staff->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="task_description">Task Description:</label>
        <textarea name="task_description" id="task_description" required></textarea>
        <br>
        <label for="deadline">Deadline:</label>
        <input type="date" name="deadline" id="deadline" required>
        <br>
        <button type="submit" name="assign_task">Assign Task</button>
    </form>

    <!-- Task List -->
    <h2>Assigned Tasks</h2>
    <table>
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Staff</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $task['id']; ?></td>
                    <td><?php echo $task['staff_name']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['deadline']; ?></td>
                    <td><?php echo ucfirst($task['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
