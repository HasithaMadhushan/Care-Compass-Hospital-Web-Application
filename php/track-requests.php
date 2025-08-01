<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

$staff_id = intval($_SESSION['user_id']);
$query = "SELECT * FROM supply_requests WHERE staff_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Resource Requests</title>
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
    <h1>Track Resource Requests</h1>

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Request Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($request = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['id']); ?></td>
                        <td><?php echo htmlspecialchars($request['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($request['reason']); ?></td>
                        <td class="status-<?php echo strtolower($request['status']); ?>">
                            <?php echo ucfirst($request['status']); ?>
                        </td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($request['created_at']))); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No resource requests made yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="staff-dashboard.php" class="back-btn">Back</a>
</body>
</html>
