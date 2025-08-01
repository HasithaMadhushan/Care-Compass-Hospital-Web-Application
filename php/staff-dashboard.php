<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: login.php');
    exit();
}
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
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
        button {
            background-color: #005cbf;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
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
    <h1>Welcome, Staff Member</h1>

    <!-- Analytics Section -->
    <div class="section">
        <h2>Analytics</h2>
        <div class="dashboard-card">
            <p><strong>Tasks Assigned:</strong> <?php echo getAssignedTasks($conn, $_SESSION['user_id']); ?></p>
            <p><strong>Tasks Completed:</strong> <?php echo getCompletedTasks($conn, $_SESSION['user_id']); ?></p>
            <p><strong>Bed Availability:</strong> <?php echo getBedAvailability($conn); ?> beds available</p>
            <p><strong>Urgent Appointments:</strong> <?php echo getUrgentAppointments($conn); ?></p>
        </div>
    </div>

    <!-- Task Management -->
    <div class="section">
        <h2>Task Management</h2>
        <div class="dashboard-card">
            <a href="view-tasks.php">View and Update Assigned Tasks</a>
            <br>
        </div>
    </div>

    <!-- Patient Management -->
    <div class="section">
        <h2>Patient Management</h2>
        <div class="dashboard-card">
            <a href="appointments-staff.php">View and Update Patient Appointments</a>
            <br>
            <a href="patients-records.php">View and Update Patient Records</a>
            <br>
        </div>
    </div>
    
    <div class="section">
        <h2>Notifications and Alerts</h2>
        <div class="dashboard-card">
            <a href="viewfeedbacks-staff.php">View Feedbacks</a><br>
            <a href="messages-staff.php">Contact Us Messages</a><br>
        </div>
    </div>

    <!-- Resource Requests -->
    <div class="section">
        <h2>Resource Requests</h2>
        <div class="dashboard-card">
            <a href="request-supplies.php">Request Medical Supplies</a>
            <br>
            <a href="track-requests.php">Track Resource Requests</a>
        </div>
    </div>
    <a href="logout.php" class="back-btn">Logout</a>
</body>
</html>

<?php
// Helper Functions
function getAssignedTasks($conn, $staff_id) {
    $query = "SELECT COUNT(*) as total FROM tasks WHERE staff_id = $staff_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getCompletedTasks($conn, $staff_id) {
    $query = "SELECT COUNT(*) as total FROM tasks WHERE staff_id = $staff_id AND status = 'completed'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getBedAvailability($conn) {
    return rand(5, 10);
}

function getUrgentAppointments($conn) {
    $query = "SELECT COUNT(*) as total FROM appointments WHERE priority = 'urgent' AND date = CURDATE()";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}
?>
