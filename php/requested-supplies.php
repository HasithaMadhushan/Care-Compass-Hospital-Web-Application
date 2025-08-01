<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$admin_message = ''; // Initialize the message variable
$error_message = ''; // Initialize the error message

// Handle actions (approve, reject, or delete supply requests)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['request_id'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    // Prepare the SQL query based on the action
    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE supply_requests SET status = 'approved', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $request_id);
    } elseif ($action === 'reject') {
        // Reject the request
        $stmt = $conn->prepare("UPDATE supply_requests SET status = 'rejected', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $request_id);
    } elseif ($action === 'delete') {
        // Delete the request
        $stmt = $conn->prepare("DELETE FROM supply_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
    } else {
        $error_message = "Invalid action.";
    }

    // Execute the prepared statement if it exists
    if (isset($stmt) && $stmt->execute()) {
        if ($action === 'delete') {
            $admin_message = "Request has been deleted.";
        } else {
            $admin_message = "Request has been " . ($action === 'approve' ? 'approved' : 'rejected') . ".";
        }
        $stmt->close();
    } elseif (isset($stmt)) {
        $error_message = "Error: " . $conn->error;
        $stmt->close();
    }
}

// Fetch all supply requests
$query = "SELECT supply_requests.*, staff.first_name AS staff_first_name, staff.last_name AS staff_last_name 
          FROM supply_requests 
          JOIN users AS staff ON supply_requests.staff_id = staff.id 
          ORDER BY supply_requests.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Supplies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
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
            color: orange;
            font-weight: bold;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        button {
            background-color: #005cbf;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
        }
        .delete-btn {
            background-color: #dc3545; /* Red background for delete button */
        }
        .delete-btn:hover {
            background-color: #c82333;
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
    </style>
</head>
<body>
    <h1>Requested Supplies</h1>

    <?php if (!empty($admin_message)): ?>
        <p id="successMessage" class="success"><?php echo htmlspecialchars($admin_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p id="errorMessage" class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Staff Name</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['staff_first_name'] . ' ' . $row['staff_last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td class="status-<?php echo htmlspecialchars($row['status']); ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit">Approve</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit">Reject</button>
                                </form>
                            <?php endif; ?>
                            <!-- Add the delete button for all requests -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No supply requests available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="admin-dashboard.php" class="back-btn">Back</a>

    <script>
        // Function to hide success/error messages after 3 seconds
        setTimeout(function() {
            var successMessage = document.getElementById('successMessage');
            var errorMessage = document.getElementById('errorMessage');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 3000);
    </script>
</body>
</html>
