<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';

// Handle shift scheduling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_shift'])) {
    $staff_id = intval($_POST['staff_id']);
    $shift_start = $conn->real_escape_string($_POST['shift_start']);
    $shift_end = $conn->real_escape_string($_POST['shift_end']);

    $query = "INSERT INTO shifts (staff_id, shift_start, shift_end) VALUES ($staff_id, '$shift_start', '$shift_end')";
    if ($conn->query($query)) {
        $message = "<p style='color: green;'>Shift assigned successfully.</p>";
    } else {
        $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch staff
$staff = $conn->query("SELECT id, name FROM staff WHERE status = 'active'");

// Fetch shift schedules
$shifts = $conn->query("SELECT shifts.*, staff.name AS staff_name FROM shifts JOIN staff ON shifts.staff_id = staff.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Scheduling</title>
</head>
<body>
    <h1>Shift Scheduling</h1>

    <!-- Success/Error Message -->
    <?php if (isset($message)) echo $message; ?>

    <!-- Shift Assignment Form -->
    <form method="POST">
        <label for="staff_id">Assign To:</label>
        <select name="staff_id" id="staff_id" required>
            <option value="">Select Staff</option>
            <?php while ($row = $staff->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="shift_start">Shift Start:</label>
        <input type="datetime-local" name="shift_start" id="shift_start" required>
        <br>
        <label for="shift_end">Shift End:</label>
        <input type="datetime-local" name="shift_end" id="shift_end" required>
        <br>
        <button type="submit" name="assign_shift">Assign Shift</button>
    </form>

    <!-- Shift Schedule -->
    <h2>Shift Schedules</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Shift ID</th>
                <th>Staff</th>
                <th>Shift Start</th>
                <th>Shift End</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($shift = $shifts->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $shift['id']; ?></td>
                    <td><?php echo $shift['staff_name']; ?></td>
                    <td><?php echo $shift['shift_start']; ?></td>
                    <td><?php echo $shift['shift_end']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
