<?php
// Start session to ensure admin is logged in
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

// Fetch all feedbacks from the database
$sql = "SELECT id, patient_name, feedback, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Feedbacks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styles for the feedback page */
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

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
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
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>View Patient Feedbacks</h1>
    <div class="feedback-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Feedback</th>
                        <th>Submitted At</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['feedback'], 0, 50)) . '...'; ?></td> <!-- Show only the first 50 characters -->
                            <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No feedbacks available.</p>
        <?php endif; ?>
        
        <a href="admin-dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
