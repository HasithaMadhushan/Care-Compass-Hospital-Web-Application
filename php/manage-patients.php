<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';

// Initialize message variables
$feedback_message = '';
$feedback_type = ''; // Either 'success' or 'error'

// Handle actions (update patient details)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Update Patient Details
        if ($action === 'update') {
            $patient_id = intval($_POST['patient_id']);
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $status = $conn->real_escape_string($_POST['status']);

            // Update query to modify the patient details
            $query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone', status = '$status' WHERE id = $patient_id AND role = 'patient'";
            if ($conn->query($query)) {
                // Set success message
                $feedback_message = 'Patient details updated successfully.';
                $feedback_type = 'success';
            } else {
                // Set error message
                $feedback_message = 'Error: ' . $conn->error;
                $feedback_type = 'error';
            }
        }
    }
}

// Handle search
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];  // No need to use real_escape_string with prepared statements
    $search_query = "WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?) AND role = 'patient'"; 
    
    // Prepare statement
    $stmt = $conn->prepare("SELECT * FROM users $search_query");
    
    // Bind parameters
    $search_term = '%' . $search . '%';
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term); 
    
    // Execute query
    $stmt->execute();
    
    // Get results
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users WHERE role = 'patient'");
}

// Fetch patients
$patients = $result;

// Export functionality (CSV)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="patients.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Status']);
    $patients_export = $conn->query("SELECT * FROM users WHERE role = 'patient'");
    while ($row = $patients_export->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }

        h1 {
            color: #005cbf;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .actions button {
            margin-right: 10px;
        }

        .bulk-actions {
            margin-top: 10px;
        }

        #update-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Feedback Message Styling */
        .feedback-message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-weight: bold;
            display: none; /* Initially hidden */
            transition: opacity 0.5s ease-in-out; /* Fade-out effect */
        }

        .success-message {
            background-color: #d4edda; /* Green background */
            color: #155724; /* Dark green text */
            border: 2px solid #c3e6cb; /* Light green border */
        }

        .error-message {
            background-color: #f8d7da; /* Red background */
            color: #721c24; /* Dark red text */
            border: 2px solid #f5c6cb; /* Light red border */
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

        .status {
            padding: 5px;
            border-radius: 5px;
        }

        .status-active {
            background-color: #28a745;
            color: #fff;
        }

        .status-archived {
            background-color: #dc3545;
            color: #fff;
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

        .actions a.delete {
            background-color: #dc3545;
        }

        .actions a.delete:hover {
            background-color: #c82333;
        }

        .modal-content input, .modal-content select, .modal-content textarea {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #005cbf;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .modal-content button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>

<h1>Manage Patients</h1>

<!-- Feedback Message Container -->
<div id="feedback-container">
    <div id="feedback-message" class="feedback-message"></div>
</div>

<!-- Search and Filter -->
<form method="GET">
    <input type="text" name="search" placeholder="Search by name, email, or phone" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
    <a href="?export=csv" style="margin-left: 10px;">Export as CSV</a>
</form>

<!-- Patient List -->
<form method="POST">
    <table class="table">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($patient = $patients->fetch_assoc()): ?>
                <tr>
                    <td><input type="checkbox" name="patient_ids[]" value="<?php echo $patient['id']; ?>"></td>
                    <td><?php echo $patient['first_name']; ?></td>
                    <td><?php echo $patient['last_name']; ?></td>
                    <td><?php echo $patient['email']; ?></td>
                    <td><?php echo $patient['phone']; ?></td>
                    <td>
                        <span class="status status-<?php echo $patient['status']; ?>">
                            <?php echo ucfirst($patient['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <!-- Edit Button -->
                        <button type="button" onclick="openUpdateModal(<?php echo $patient['id']; ?>, '<?php echo $patient['first_name']; ?>', '<?php echo $patient['last_name']; ?>', '<?php echo $patient['email']; ?>', '<?php echo $patient['phone']; ?>', '<?php echo $patient['status']; ?>')">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</form>

<!-- Update Modal -->
<div id="update-modal">
    <div class="modal-content">
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="patient_id" id="update-patient-id">
            <label>First Name: <input type="text" name="first_name" id="update-first-name"></label><br>
            <label>Last Name: <input type="text" name="last_name" id="update-last-name"></label><br>
            <label>Email: <input type="email" name="email" id="update-email"></label><br>
            <label>Phone: <input type="text" name="phone" id="update-phone"></label><br>
            <label>Status: 
                <select name="status" id="update-status">
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
            </label><br>
            <button type="submit">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openUpdateModal(id, first_name, last_name, email, phone, status) {
        document.getElementById('update-patient-id').value = id;
        document.getElementById('update-first-name').value = first_name;
        document.getElementById('update-last-name').value = last_name;
        document.getElementById('update-email').value = email;
        document.getElementById('update-phone').value = phone;
        document.getElementById('update-status').value = status;
        document.getElementById('update-modal').style.display = 'block';
    }

    function closeUpdateModal() {
        document.getElementById('update-modal').style.display = 'none';
    }

    // Show feedback message
    function showFeedbackMessage(message, type) {
        const feedbackMessage = document.getElementById('feedback-message');
        
        // Clear any previous message
        feedbackMessage.textContent = message;
        
        // Assign class based on type (success or error)
        if (type === 'success') {
            feedbackMessage.classList.remove('error-message');
            feedbackMessage.classList.add('success-message');
        } else {
            feedbackMessage.classList.remove('success-message');
            feedbackMessage.classList.add('error-message');
        }

        // Display the message
        feedbackMessage.style.display = 'block';

        // Automatically hide the message after 3 seconds
        setTimeout(() => {
            feedbackMessage.style.display = 'none';
        }, 3000); // 3 seconds
    }

    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="patient_ids[]"]');
        for (const checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>

<?php if ($feedback_message): ?>
    <script>
        // Display the feedback message after the page loads
        showFeedbackMessage('<?php echo $feedback_message; ?>', '<?php echo $feedback_type; ?>');
    </script>
<?php endif; ?>

<a href="admin-dashboard.php" class="back-btn">Back</a>
</body>
</html>
