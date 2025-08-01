<?php
session_start();
include 'config.php'; // Include database connection

// Check if the staff is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

// Get the logged-in staff member's ID from the session
$staff_id = $_SESSION['user_id'];

// Fetch tasks assigned to the logged-in staff member
$tasksQuery = "SELECT * FROM tasks WHERE staff_id = $staff_id";
$tasksResult = $conn->query($tasksQuery);

// Update task status when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $task_id = intval($_POST['task_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Update task status in the database
    $updateTaskQuery = "UPDATE tasks SET status = '$status' WHERE id = $task_id AND staff_id = $staff_id";
    if ($conn->query($updateTaskQuery)) {
        $feedback_message = 'Task status updated successfully.';
        $feedback_type = 'success';
    } else {
        $feedback_message = 'Error: ' . $conn->error;
        $feedback_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }

        h1 {
            color: #005cbf;
        }

        .feedback-message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-weight: bold;
            display: none;
            transition: opacity 0.5s ease-in-out;
        }
        form button {
    padding: 10px 20px;
    background-color: #005cbf; /* Blue color */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #004494; /* Darker blue for hover effect */
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

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
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
    </style>
</head>
<body>

<h1>Your Assigned Tasks</h1>

<!-- Feedback Message -->
<div id="feedback-container">
    <div id="feedback-message" class="feedback-message"></div>
</div>

<!-- Task List -->
<h2>Task Details</h2>
<table class="table">
    <thead>
        <tr>
            <th>Task Description</th>
            <th>Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($task = $tasksResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['task_description']); ?></td>
                <td><?php echo ucfirst($task['status']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="hidden" name="action" value="update_status">
                        <select name="status" required>
                            <option value="pending" <?php echo ($task['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="in-progress" <?php echo ($task['status'] === 'in-progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo ($task['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

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
</script>

<?php if (isset($feedback_message)): ?>
    <script>
        showFeedbackMessage('<?php echo $feedback_message; ?>', '<?php echo $feedback_type; ?>');
    </script>
<?php endif; ?>

<a href="staff-dashboard.php" class="back-btn">Back</a>
</body>
</html>
