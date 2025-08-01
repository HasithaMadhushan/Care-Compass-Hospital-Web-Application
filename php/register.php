<?php
session_start();
include 'config.php'; // Include the database configuration file

$errors = []; // Store error messages
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Validate input fields
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);

    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }

    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Phone number must be 10 digits.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the 'users' table
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, role) 
                VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$phone', 'patient')";
        if ($conn->query($sql)) {
            // Get the user ID and set session variables
            $user_id = $conn->insert_id;
            $_SESSION['patient_id'] = $user_id;  // Store the patient's user ID in session
            $_SESSION['user_role'] = 'patient';  // Set the user's role as 'patient'

            // Optionally, store the first name or other data if needed
            $_SESSION['patient_first_name'] = $first_name;

            $success_message = "Registration successful! Redirecting to the homepage...";

            // Redirect to the homepage after successful registration and login
            header("Refresh: 3; url=../index.php");
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            margin: auto;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #005cbf;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #004494;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .form-container {
            margin-top: 50px;
        }
        @media (max-width: 400px) {
            form {
                width: 100%;
                padding: 15px;
            }
        }
        .back-btn {
    display: inline-block;
    background-color: #6c757d;
    color: white;
    padding: 2px;
    border-radius: 4px;
    text-decoration: none; /* Removes underline */
    text-align: center;
    width: 100%; /* Makes it fill the width of the form */
    font-size: 16px; /* Matches form button font size */
    margin-top: 10px; /* Adds space between the button and form */
}

.back-btn:hover {
    background-color: #5a6268; /* Darker shade on hover */
}

@media (max-width: 400px) {
    .back-btn {
        width: 100%; /* Ensures button stays full-width on small screens */
    }
}
    </style>
</head>
<body>
    <h1>Register</h1>
    <div class="form-container">
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success">
                <p><?php echo htmlspecialchars($success_message); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">Register</button>
            <a href="../index.php" class="back-btn">Back to Home</a>
        </form>
    </div>
</body>
</html>
