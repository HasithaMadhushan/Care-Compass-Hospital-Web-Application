<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}
include 'config.php';
// Fetch all messages from the database
$sql = "SELECT * FROM messages ORDER BY submitted_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - View Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color:rgb(241, 242, 244);
            padding: 20px;
            text-align: center;
            color: black;
        }
        header h1 {
            margin: 0;
            font-size: 2em;
        }
        h1 {
            color: #005cbf;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 30px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #005cbf;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-btn {
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
        /* Red color for delete button */
        .actions a.delete {
            background-color: #dc3545;
        }
        .actions a.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <!-- Header -->

    <!-- Main Content -->
    <div class="container">
        <!-- Messages Table -->
        <h1>Messages</h1>

        <?php
        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th></tr>';

            // Fetch and display each message
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . nl2br(htmlspecialchars($row['message'])) . '</td>';
                echo '<td>' . $row['submitted_at'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No messages found.</p>';
        }
        ?>

        <a href="staff-dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
