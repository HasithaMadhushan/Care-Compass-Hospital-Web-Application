<?php
session_start();
include 'config.php'; // Include database connection

// Login handler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']); // Sanitize email input
    $password = $_POST['password']; // Get the password input

    // Query to fetch user details including role
    $query = "SELECT id, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc(); // Fetch user data

        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Password verification successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // If the user is a patient, store their patient_id
            if ($user['role'] == 'patient') {
                $_SESSION['patient_id'] = $user['id'];

                // Redirect to the home page instead of the patient dashboard
                header('Location: ../index.php'); // Modify this line to redirect to the home page
                exit();
            }

            // Redirect based on user role for admin and staff
            switch ($user['role']) {
                case 'admin':
                    header('Location: admin-dashboard.php');
                    exit();
                case 'staff':
                    header('Location: staff-dashboard.php');
                    exit();
                default:
                    echo "<p style='color: red;'>Invalid role. Please contact support.</p>";
                    exit();
            }
        } else {
            // Incorrect password
            $error = "Incorrect password. Please try again.";
        }
    } else {
        // No user found with the given email
        $error = $result ? "No account found with that email. Please register." : "Query error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
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
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004494;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
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
    <h1>Login</h1>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">Login</button>
        <a href="../index.php" class="back-btn">Back to Home</a>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
