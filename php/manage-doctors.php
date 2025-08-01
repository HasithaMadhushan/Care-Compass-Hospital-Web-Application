<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        $name = $_POST['name'];
        $specialization = $_POST['specialization'];
        $qualifications = $_POST['qualifications'];
        $contact_details = $_POST['contact_details'];
        $availability = $_POST['availability'];
        $branch = $_POST['branch'];

        $sql = "INSERT INTO doctors (name, specialization, qualifications, contact_details, availability, branch) 
                VALUES ('$name', '$specialization', '$qualifications', '$contact_details', '$availability', '$branch')";
        $conn->query($sql);
    } elseif ($action === 'delete') {
        $doctor_id = $_POST['doctor_id'];
        $sql = "DELETE FROM doctors WHERE id = $doctor_id";
        $conn->query($sql);
    }
}

$result = $conn->query("SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
</head>
<body>
    <h1>Manage Doctors</h1>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Specialization: <input type="text" name="specialization" required></label><br>
        <label>Qualifications: <input type="text" name="qualifications" required></label><br>
        <label>Contact Details: <input type="text" name="contact_details" required></label><br>
        <label>Availability: <input type="text" name="availability" required></label><br>
        <label>Branch: <input type="text" name="branch" required></label><br>
        <button type="submit">Add Doctor</button>
    </form>

    <h2>Doctor List</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo $row['name'] . " (" . $row['specialization'] . ")"; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="doctor_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
