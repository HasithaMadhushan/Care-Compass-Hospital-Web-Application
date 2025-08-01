<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php'; // Include your database connection

$success_message = '';
$error_message = '';
$form_submitted = false; // Flag to track form submission

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_name'], $_POST['feedback'])) {
    $patient_name = trim($_POST['patient_name']);
    $feedback = trim($_POST['feedback']);

    if (!empty($patient_name) && !empty($feedback)) {
        // Insert the feedback into the database
        $stmt = $conn->prepare("INSERT INTO feedback (patient_name, feedback) VALUES (?, ?)");
        $stmt->bind_param("ss", $patient_name, $feedback);

        if ($stmt->execute()) {
            $success_message = "Thank you for your feedback!";
            $form_submitted = true; // Mark form as submitted
        } else {
            $error_message = "Error: " . $conn->error;
            $form_submitted = true; // Mark form as submitted
        }
        $stmt->close();
    } else {
        $error_message = "Please fill out all fields.";
        $form_submitted = true; // Mark form as submitted
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Feedback</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f8f9fc;
            color: #333;
        }

        header {
            background-color: #005cbf;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 0;
            font-size: 28px;
        }

        .feedback-section {
            padding: 40px 20px;
            text-align: center;
            max-width: 700px;
            margin: 30px auto;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .feedback-section h2 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #005cbf;
        }

        .feedback-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .feedback-form label {
            font-weight: bold;
            margin-bottom: 8px;
            text-align: left;
            width: 100%;
            color: #333;
        }

        .feedback-form input,
        .feedback-form textarea {
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }

        .feedback-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .feedback-form button {
            padding: 12px 25px;
            background-color: #005cbf;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .feedback-form button:hover {
            background-color: #004494;
        }

        .success, .error {
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            font-weight: bold;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .back-btn {
            padding: 12px 25px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 30px;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .feedback-form {
            display: <?php echo $form_submitted ? 'none' : 'block'; ?>;
        }

        .back-btn {
            display: <?php echo $form_submitted ? 'inline-block' : 'none'; ?>;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @media (max-width: 600px) {
            .feedback-section {
                padding: 25px 15px;
            }

            .feedback-form input,
            .feedback-form textarea {
                font-size: 14px;
            }

            .feedback-form button {
                font-size: 14px;
            }

            .back-btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Care Compass Hospitals</h1>
    </header>

    <!-- Feedback Section -->
    <section class="feedback-section">
        <h2>We Value Your Feedback</h2>

        <!-- Display success or error message here -->
        <?php if (!empty($success_message)): ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <!-- Feedback Form -->
        <form action="submit_feedback.php" method="POST" class="feedback-form">
            <label for="patient_name">Your Name:</label>
            <input type="text" name="patient_name" id="patient_name" required>

            <label for="feedback">Your Feedback:</label>
            <textarea name="feedback" id="feedback" rows="4" required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>

        <!-- Back to Home Button -->
        <a href="../index.php" class="back-btn">Back to Home</a>
    </section>

</body>
</html>
