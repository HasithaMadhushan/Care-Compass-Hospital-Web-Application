<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php'; // Include your database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            color: #333;
            border-bottom: 2px solid #005cbf;
            padding-bottom: 5px;
        }
        .dashboard-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        a {
            text-decoration: none;
            color: #005cbf;
            font-weight: bold;
        }
        a:hover {
            color: #004494;
        }
        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn {
            background-color:rgb(227, 11, 11);
            color: black;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <div class="section">
        <h2>Analytics and Reports</h2>
        <div class="dashboard-card">
            <p><strong>Patient Volume:</strong> <?php echo getPatientVolume($conn); ?> patients</p>
            <p><strong>Staff Efficiency:</strong> <?php echo calculateStaffEfficiency($conn); ?>%</p>
            <p><strong>Bed Availability:</strong> <?php echo getBedAvailability($conn); ?> beds available</p>
            <p><strong>Admission Rates:</strong> <?php echo getAdmissionRates($conn); ?>%</p>
        </div>
    </div>

    <div class="section">
        <h2>User Management</h2>
        <div class="dashboard-card">
            <a href="manage-staff.php">Manage Staff Accounts</a>
            <br>
            <a href="manage-patients.php">Manage Patient Records</a>
        </div>
    </div>

    <div class="section">
        <h2>Appointment and Schedule Management</h2>
        <div class="dashboard-card">
            <a href="appointments-admin.php">View and Manage Appointments</a>
            <br>
            <a href="assign-doctors.php">Assign Doctors to Patients</a>
        </div>
    </div>

    <div class="section">
        <h2>Resource Management</h2>
        <div class="dashboard-card">
            <a href="manage-inventory.php">View and Manage Inventory</a>
            <br>
            <a href="requested-supplies.php">Requested Supplies</a>
        </div>
    </div>

    <div class="section">
        <h2>Notifications and Alerts</h2>
        <div class="dashboard-card">
            <a href="viewfeedbacks.php">View Feedbacks</a><br>
            <a href="messages.php">Contact Us Messages</a><br>
        </div>
    </div>

    <div class="section">
        <h2>Access Control</h2>
        <div class="dashboard-card">
            <a href="insert-doctors.php">Add Doctors</a>
            <br>
            <a href="manage-roles.php">Manage Role-based Permissions</a>
        </div>
    </div>
    <a href="logout.php" class="back-btn">Logout</a>
</body>
</html>

<?php
// Example functions for data retrieval
function getPatientVolume($conn) {
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'patient'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function calculateStaffEfficiency($conn) {
    // Placeholder logic for staff efficiency
    return rand(70, 95); // Random efficiency value for example
}

function getBedAvailability($conn) {
    return rand(5, 10);
}

function getAdmissionRates($conn) {
    // Placeholder logic for admission rates
    return rand(50, 100); // Random percentage value for example
}
?>
