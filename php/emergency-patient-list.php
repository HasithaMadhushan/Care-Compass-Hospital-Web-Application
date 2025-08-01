<?php
session_start();

// Ensure user is logged in and has the staff role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'attend') {
            $case_id = intval($_POST['case_id']);
            $query = "UPDATE emergency_cases SET status = 'attended' WHERE id = $case_id";
            if ($conn->query($query)) {
                $message = "Emergency case marked as attended.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($action === 'add_note') {
            $case_id = intval($_POST['case_id']);
            $note = $conn->real_escape_string($_POST['note']);
            $query = "UPDATE emergency_cases SET notes = '$note' WHERE id = $case_id";
            if ($conn->query($query)) {
                $message = "Note added successfully.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}

// Handle filters
$status_filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$priority_filter = isset($_GET['priority']) ? $conn->real_escape_string($_GET['priority']) : '';

// Build filter conditions
$conditions = [];
if (!empty($status_filter)) {
    $conditions[] = "status = '$status_filter'";
}
if (!empty($priority_filter)) {
    $conditions[] = "priority = '$priority_filter'";
}
$where_clause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

// Fetch emergency cases
$query = "SELECT emergency_cases.*, patients.name AS patient_name 
          FROM emergency_cases 
          JOIN patients ON emergency_cases.patient_id = patients.id
          $where_clause 
          ORDER BY priority DESC, created_at DESC";
$cases = $conn->query($query);

// Fetch priorities for filtering
$priorities = ['High', 'Medium', 'Low'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Patient List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .filters {
            margin-bottom: 20px;
        }
        .filters select, .filters button {
            margin-right: 10px;
        }
        .priority-high {
            color: red;
            font-weight: bold;
        }
        .priority-medium {
            color: orange;
            font-weight: bold;
        }
        .priority-low {
            color: green;
            font-weight: bold;
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
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Emergency Patient List</h1>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Filters -->
    <form method="GET" class="filters">
        <label>Status:
            <select name="status">
                <option value="">All</option>
                <option value="pending" <?php if ($status_filter === 'pending') echo 'selected'; ?>>Pending</option>
                <option value="attended" <?php if ($status_filter === 'attended') echo 'selected'; ?>>Attended</option>
            </select>
        </label>
        <label>Priority:
            <select name="priority">
                <option value="">All</option>
                <?php foreach ($priorities as $priority): ?>
                    <option value="<?php echo strtolower($priority); ?>" <?php if ($priority_filter === strtolower($priority)) echo 'selected'; ?>>
                        <?php echo $priority; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Filter</button>
    </form>

    <!-- Emergency Cases -->
    <table>
        <thead>
            <tr>
                <th>Patient</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($case = $cases->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($case['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($case['description']); ?></td>
                    <td class="priority-<?php echo strtolower($case['priority']); ?>"><?php echo ucfirst($case['priority']); ?></td>
                    <td><?php echo ucfirst($case['status']); ?></td>
                    <td><?php echo $case['notes'] ? htmlspecialchars($case['notes']) : 'No notes'; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="attend">
                            <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                            <button type="submit">Mark as Attended</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="add_note">
                            <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                            <input type="text" name="note" placeholder="Add note" required>
                            <button type="submit">Add Note</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
