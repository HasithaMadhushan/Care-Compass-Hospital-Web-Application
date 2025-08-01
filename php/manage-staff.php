<?php
session_start();
include 'config.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch staff members from the database
$staffQuery = "SELECT * FROM users WHERE role = 'staff' AND status = 'active'";
$staffResult = $conn->query($staffQuery);

// Fetch tasks and their status for each staff member
$tasksQuery = "SELECT * FROM tasks WHERE status IN ('pending', 'in-progress', 'completed')";
$tasksResult = $conn->query($tasksQuery);

// Edit staff member details (update action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $staff_id = intval($_POST['staff_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $updateQuery = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', phone = '$phone', status = '$status' WHERE id = $staff_id AND role = 'staff'";
    if ($conn->query($updateQuery)) {
        $feedback_message = 'Staff details updated successfully.';
        $feedback_type = 'success';
    } else {
        $feedback_message = 'Error: ' . $conn->error;
        $feedback_type = 'error';
    }
}

// Assign task to a staff member (task assignment action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_task') {
    $staff_id = intval($_POST['staff_id']);
    $task_description = $conn->real_escape_string($_POST['task_description']);
    $status = 'pending'; // Default status is pending
    
    $taskQuery = "INSERT INTO tasks (staff_id, task_description, status) VALUES ($staff_id, '$task_description', '$status')";
    if ($conn->query($taskQuery)) {
        $feedback_message = 'Task assigned successfully.';
        $feedback_type = 'success';
    } else {
        $feedback_message = 'Error: ' . $conn->error;
        $feedback_type = 'error';
    }
}

// Delete task (delete action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_task') {
    $task_id = intval($_POST['task_id']);
    
    $deleteTaskQuery = "DELETE FROM tasks WHERE id = $task_id";
    if ($conn->query($deleteTaskQuery)) {
        $feedback_message = 'Task deleted successfully.';
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
    <title>Manage Staff</title>
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

        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .status {
            padding: 5px;
            border-radius: 5px;
        }

        .status-pending {
            background-color: #ffcc00;
            color: #fff;
        }

        .status-in-progress {
            background-color: #17a2b8;
            color: #fff;
        }

        .status-completed {
            background-color: #28a745;
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

        .actions a.delete {
            background-color: #dc3545;
        }

        .actions a.delete:hover {
            background-color: #c82333;
        }

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

        .modal-content input, .modal-content select, .modal-content textarea {
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

<h1>Manage Staff</h1>

<!-- Feedback Message -->
<div id="feedback-container">
    <div id="feedback-message" class="feedback-message"></div>
</div>

<!-- Staff Members List -->
<h2>Staff Members</h2>
<table class="table">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Task Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($staff = $staffResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($staff['first_name']); ?></td>
                <td><?php echo htmlspecialchars($staff['last_name']); ?></td>
                <td><?php echo htmlspecialchars($staff['email']); ?></td>
                <td><?php echo htmlspecialchars($staff['phone']); ?></td>
                <td>
                    <?php
                        // Fetch the task(s) assigned to this staff member
                        $taskQuery = "SELECT * FROM tasks WHERE staff_id = " . $staff['id'] . " AND status IN ('pending', 'in-progress', 'completed')";
                        $taskResult = $conn->query($taskQuery);

                        // Display the task statuses
                        if ($taskResult->num_rows > 0) {
                            while ($task = $taskResult->fetch_assoc()) {
                                echo "<p>Task: " . htmlspecialchars($task['task_description']) . " - Status: ";
                                echo "<span class='status status-" . $task['status'] . "'>" . ucfirst($task['status']) . "</span>";
                                echo "<form method='POST' style='display:inline;'>
                                    <input type='hidden' name='task_id' value='" . $task['id'] . "'>
                                    <input type='hidden' name='action' value='delete_task'>
                                    <button type='submit' class='delete-btn'>Delete</button>
                                </form></p>";
                            }
                        } else {
                            echo "No tasks assigned";
                        }
                    ?>
                </td>
                <td class="actions">
                    <a href="#" onclick="openUpdateModal(<?php echo $staff['id']; ?>, '<?php echo $staff['first_name']; ?>', '<?php echo $staff['last_name']; ?>', '<?php echo $staff['phone']; ?>')">Edit</a>
                    <a href="#" onclick="openAssignTaskModal(<?php echo $staff['id']; ?>)">Assign Task</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Update Staff Modal -->
<div id="update-modal" class="modal">
    <div class="modal-content">
        <h3>Edit Staff Details</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="staff_id" id="staff-id">
            <label>First Name:</label>
            <input type="text" name="first_name" id="first-name" required><br>
            <label>Last Name:</label>
            <input type="text" name="last_name" id="last-name" required><br>
            <label>Phone:</label>
            <input type="text" name="phone" id="phone" required><br>
            <label>Status:</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="archived">Archived</option>
            </select><br>
            <button type="submit">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Assign Task Modal -->
<div id="assign-task-modal" class="modal">
    <div class="modal-content">
        <h3>Assign Task to Staff</h3>
        <form method="POST">
            <input type="hidden" name="action" value="assign_task">
            <input type="hidden" name="staff_id" id="assign-task-staff-id">
            <label>Task Description:</label><br>
            <textarea name="task_description" id="task-description" rows="4" required></textarea><br>
            <button type="submit">Assign Task</button>
            <button type="button" onclick="closeAssignTaskModal()">Cancel</button>
        </form>
    </div>
</div>

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

    // Open Update Modal
    function openUpdateModal(staff_id, first_name, last_name, phone) {
        document.getElementById('staff-id').value = staff_id;
        document.getElementById('first-name').value = first_name;
        document.getElementById('last-name').value = last_name;
        document.getElementById('phone').value = phone;
        document.getElementById('update-modal').style.display = 'flex';
    }

    // Close Update Modal
    function closeUpdateModal() {
        document.getElementById('update-modal').style.display = 'none';
    }

    // Open Assign Task Modal
    function openAssignTaskModal(staff_id) {
        document.getElementById('assign-task-staff-id').value = staff_id;
        document.getElementById('assign-task-modal').style.display = 'flex';
    }

    // Close Assign Task Modal
    function closeAssignTaskModal() {
        document.getElementById('assign-task-modal').style.display = 'none';
    }
</script>

<?php if (isset($feedback_message)): ?>
    <script>
        showFeedbackMessage('<?php echo $feedback_message; ?>', '<?php echo $feedback_type; ?>');
    </script>
<?php endif; ?>

<a href="admin-dashboard.php" class="back-btn">Back</a>
</body>
</html>
