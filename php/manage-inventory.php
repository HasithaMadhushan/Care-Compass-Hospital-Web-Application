<?php
session_start();
include 'config.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all supply requests
$query = "SELECT sr.*, u.first_name, u.last_name 
          FROM supply_requests sr 
          LEFT JOIN users u ON sr.staff_id = u.id";
$result = $conn->query($query);

// Handle quantity edit or delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Edit quantity
    if (isset($_POST['edit_quantity'])) {
        $request_id = intval($_POST['request_id']);
        $quantity = intval($_POST['quantity']);
        
        // Update the quantity of the supply request
        $updateQuery = "UPDATE supply_requests 
                        SET quantity = $quantity 
                        WHERE id = $request_id";
        
        if ($conn->query($updateQuery)) {
            $feedback_message = "Quantity updated successfully!";
            $feedback_type = "success";
        } else {
            $feedback_message = "Error: " . $conn->error;
            $feedback_type = "error";
        }

        // Refresh the page after updating the quantity
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();  // Ensure that the rest of the code doesn't run after redirect
    }
    // Delete supply request
    elseif (isset($_POST['delete_request'])) {
        $request_id = intval($_POST['request_id']);
        
        // Delete the supply request
        $deleteQuery = "DELETE FROM supply_requests WHERE id = $request_id";
        
        if ($conn->query($deleteQuery)) {
            $feedback_message = "Supply request deleted successfully!";
            $feedback_type = "success";
        } else {
            $feedback_message = "Error: " . $conn->error;
            $feedback_type = "error";
        }

        // Refresh the page after deleting the request
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();  // Ensure that the rest of the code doesn't run after redirect
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard: Manage Inventory</title>
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

        .actions a {
            padding: 5px 10px;
            background-color: #005cbf;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .actions a:hover {
            background-color: #004494;
        }

        .actions a.reject {
            background-color: #dc3545;
        }

        .actions a.reject:hover {
            background-color: #c82333;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .edit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Manage Inventory - Supply Requests</h1>

<!-- Feedback Message -->
<div id="feedback-container">
    <div id="feedback-message" class="feedback-message"></div>
</div>

<!-- Supply Requests Table -->
<table class="table">
    <thead>
        <tr>
            <th>Staff Member</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($request = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['item_name']); ?></td>
                    <td>
                        <!-- Edit Quantity Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $request['quantity']; ?>" required min="1">
                            <input type="hidden" name="edit_quantity" value="1">
                            <button type="submit" class="edit-btn">Update Quantity</button>
                        </form>
                    </td>
                    <td>
                        <span class="status status-<?php echo strtolower($request['status']); ?>">
                            <?php echo ucfirst($request['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <!-- Delete Supply Request Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <input type="hidden" name="delete_request" value="1">
                            <button type="submit" class="reject">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No supply requests found.</td>
            </tr>
        <?php endif; ?>
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

<a href="admin-dashboard.php" class="back-btn">Back</a>

</body>
</html>
