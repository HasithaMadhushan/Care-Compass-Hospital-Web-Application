<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';

$message = ""; // Message container

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Add new user
        if ($_POST['action'] === 'add_user') {
            $first_name = trim($conn->real_escape_string($_POST['first_name']));
            $last_name = trim($conn->real_escape_string($_POST['last_name']));
            $email = trim($conn->real_escape_string($_POST['email']));
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $conn->real_escape_string($_POST['role']);

            if (!empty($first_name) && !empty($last_name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && in_array($role, ['admin', 'staff', 'doctor', 'patient'])) {
                $email_check_query = "SELECT id FROM users WHERE email = '$email'";
                $result = $conn->query($email_check_query);

                if ($result && $result->num_rows > 0) {
                    $message = "<p class='error'>Email already exists. Please use a different email.</p>";
                } else {
                    // Insert user
                    $user_query = "INSERT INTO users (first_name, last_name, email, password, role, created_at) VALUES ('$first_name', '$last_name', '$email', '$password', '$role', NOW())";
                    if ($conn->query($user_query)) {
                        $message = "<p class='success'>User added successfully.</p>";
                    } else {
                        $message = "<p class='error'>Error adding user: " . $conn->error . "</p>";
                    }
                }
            } else {
                $message = "<p class='error'>Invalid input. Please ensure all fields are valid.</p>";
            }
        }

        // Delete user and role
        elseif ($_POST['action'] === 'delete_role') {
            $user_id = intval($_POST['user_id']);
            $delete_query = "DELETE FROM users WHERE id = $user_id";
            if ($conn->query($delete_query)) {
                $message = "<p class='success'>User and role deleted successfully.</p>";
            } else {
                $message = "<p class='error'>Error deleting user: " . $conn->error . "</p>";
            }
        }

                // Edit user details
        elseif ($_POST['action'] === 'edit_user') {
            $user_id = intval($_POST['user_id']);
            $first_name = trim($conn->real_escape_string($_POST['first_name']));
            $last_name = trim($conn->real_escape_string($_POST['last_name']));
            $email = trim($conn->real_escape_string($_POST['email']));

            // Ensure the input is valid
            if (!empty($first_name) && !empty($last_name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Update user details excluding the role
                $update_query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE id = $user_id";
                if ($conn->query($update_query)) {
                    $message = "<p class='success'>User details updated successfully.</p>";
                } else {
                    $message = "<p class='error'>Error updating user: " . $conn->error . "</p>";
                }
            } else {
                $message = "<p class='error'>Invalid input. Please ensure all fields are valid.</p>";
            }
        }
    }
}

// Fetch all users and their roles
$user_query = "SELECT id, first_name, last_name, email, role FROM users ORDER BY first_name ASC";
$users = $conn->query($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 2em;
            text-align: center;
            padding: 20px;
            background-color: #005cbf;
            color: white;
            margin-bottom: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 30px;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #005cbf;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #004494;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 2px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        table td {
            background-color: #fff;
        }

        .action-btn {
    padding: 2px 2px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

        .action-btn:hover {
            background-color: #0056b3;
        }

        .back-btn {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        /* Edit Form Styles */
        .edit-form input, .edit-form select {
            width: 50%;
            margin-bottom: 15px;
        }

        #edit-user-form {
            display: none;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body>
    <h1>Manage Roles</h1>

    <div class="container">

        <!-- Display success/error messages -->
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

        <!-- Add New User Form -->
        <form method="POST">
            <input type="hidden" name="action" value="add_user">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="patient">Patient</option>
            </select><br><br>
            <button type="submit">Add User</button>
        </form>

        <h2>Current Users and Roles</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="action-btn" onclick="showEditForm(<?php echo $user['id']; ?>, '<?php echo addslashes($user['first_name']); ?>', '<?php echo addslashes($user['last_name']); ?>', '<?php echo addslashes($user['email']); ?>', '<?php echo addslashes($user['role']); ?>')">Edit</button>

                                <!-- Delete Form -->
                                <form method="POST">
                                    <input type="hidden" name="action" value="delete_role">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="action-btn" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Edit User Form (Initially Hidden) -->
<div id="edit-user-form">
    <h2>Edit User</h2>
    <form method="POST" class="edit-form">
        <input type="hidden" name="action" value="edit_user">
        <input type="hidden" name="user_id" id="edit-user-id">

        <label for="edit-first-name">First Name:</label>
        <input type="text" name="first_name" id="edit-first-name" required><br><br>

        <label for="edit-last-name">Last Name:</label>
        <input type="text" name="last_name" id="edit-last-name" required><br><br>

        <label for="edit-email">Email:</label>
        <input type="email" name="email" id="edit-email" required><br><br>

        <!-- Removed Role Dropdown -->

        <button type="submit">Update User</button>
    </form>
    <button onclick="closeEditForm()">Cancel</button>
</div>

        <a href="admin-dashboard.php" class="back-btn">Back</a>

    </div>

    <script>
        function showEditForm(id, firstName, lastName, email, role) {
            document.getElementById('edit-user-form').style.display = 'block';
            document.getElementById('edit-user-id').value = id;
            document.getElementById('edit-first-name').value = firstName;
            document.getElementById('edit-last-name').value = lastName;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role;
        }

        function closeEditForm() {
            document.getElementById('edit-user-form').style.display = 'none';
        }
    </script>
</body>
</html>
