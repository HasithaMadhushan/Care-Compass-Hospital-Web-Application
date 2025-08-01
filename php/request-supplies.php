<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

include 'config.php';

$feedback_message = ''; // Initialize variable for feedback message
$feedback_type = ''; // Initialize variable for feedback type

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $reason = trim($_POST['reason']);
    $staff_id = intval($_SESSION['user_id']);

    if (!empty($item_name) && $quantity > 0 && !empty($reason)) {
        $stmt = $conn->prepare("INSERT INTO supply_requests (staff_id, item_name, quantity, reason, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("isis", $staff_id, $item_name, $quantity, $reason);

        if ($stmt->execute()) {
            $feedback_message = "Supply request submitted successfully.";
            $feedback_type = 'success'; // Set success message type
        } else {
            $feedback_message = "Error: " . $conn->error;
            $feedback_type = 'error'; // Set error message type
        }
        $stmt->close();
    } else {
        $feedback_message = "All fields are required, and quantity must be greater than zero.";
        $feedback_type = 'error'; // Set error message type
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Medical Supplies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 50%;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container textarea, .form-container button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #005cbf;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #004494;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
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
        .feedback-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            display: block;
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
    </style>
</head>
<body>
    <h1>Request Medical Supplies</h1>

    <!-- Feedback Message -->
    <?php if ($feedback_message): ?>
        <div id="feedback-message" class="feedback-message <?php echo $feedback_type === 'success' ? 'success-message' : 'error-message'; ?>">
            <?php echo htmlspecialchars($feedback_message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-container">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="reason">Reason for Request:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>

        <button type="submit">Submit Request</button>
    </form>

    <a href="staff-dashboard.php" class="back-btn">Back</a>

    <script>
        // Automatically hide the feedback message after 3 seconds
        const feedbackMessage = document.getElementById('feedback-message');
        if (feedbackMessage) {
            setTimeout(() => {
                feedbackMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
