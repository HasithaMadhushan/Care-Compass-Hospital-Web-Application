<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

$success_message = '';
$error_message = '';

// Handle form submission to add a new doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_doctor') {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $qualifications = trim($_POST['qualifications']);
    $branch = trim($_POST['branch']);
    $availability = trim($_POST['availability']);

    // Handle the file upload for the doctor's picture
    $doctor_picture = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // Validate the image file
        $image_name = $_FILES['picture']['name'];
        $image_tmp_name = $_FILES['picture']['tmp_name'];
        $image_size = $_FILES['picture']['size'];
        $image_error = $_FILES['picture']['error'];

        // Allowed file extensions and max size of 5MB
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        if (in_array($image_ext, $allowed_extensions) && $image_size <= 5000000) {
            // Generate a unique name for the image
            $new_image_name = uniqid('', true) . '.' . $image_ext;
            $upload_dir = 'uploads/'; // Folder where images will be saved

            if (move_uploaded_file($image_tmp_name, $upload_dir . $new_image_name)) {
                $doctor_picture = $new_image_name; // Store the image name in the database
            } else {
                $error_message = "Error uploading the image.";
            }
        } else {
            $error_message = "Invalid image file or file size exceeds limit.";
        }
    }

    if (!empty($name) && !empty($specialization) && !empty($qualifications) && !empty($branch) && !empty($availability)) {
        // Prepare SQL query to insert doctor into the database
        $stmt = $conn->prepare("INSERT INTO doctors (name, specialization, qualifications, branch, availability, picture) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $specialization, $qualifications, $branch, $availability, $doctor_picture);

        if ($stmt->execute()) {
            $success_message = "Doctor added successfully.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}

// Handle delete doctor request
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $doctor_id = intval($_GET['delete']);
    
    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    
    if ($stmt->execute()) {
        $success_message = "Doctor deleted successfully.";
    } else {
        $error_message = "Error deleting doctor: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all doctors
$query = "SELECT * FROM doctors";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
        .form-container input, .form-container textarea, .form-container select, .form-container button {
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete {
            color: white;
            background-color: #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .delete:hover {
            background-color: #c82333;
        }
        .doctor-picture {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h1>Manage Doctors</h1>

    <!-- Success and Error Messages -->
    <?php if (!empty($success_message)): ?>
        <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Add Doctor Form -->
    <h2>Add New Doctor</h2>
    <form method="POST" enctype="multipart/form-data" class="form-container">
        <input type="hidden" name="action" value="add_doctor">
        <label for="name">Doctor's Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="specialization">Specialization:</label>
        <input type="text" id="specialization" name="specialization" required>

        <label for="qualifications">Qualifications:</label>
        <input type="text" id="qualifications" name="qualifications" required>

        <label for="branch">Branch:</label>
        <input type="text" id="branch" name="branch" required>

        <label for="availability">Availability:</label>
        <input type="text" id="availability" name="availability" required>

        <label for="picture">Upload Doctor's Picture:</label>
        <input type="file" id="picture" name="picture" accept="image/*">

        <button type="submit">Add Doctor</button>
    </form>

    <!-- Doctors List -->
    <h2>Doctors List</h2>
    <table>
        <thead>
            <tr>
                <th>Doctor's Name</th>
                <th>Specialization</th>
                <th>Qualifications</th>
                <th>Branch</th>
                <th>Availability</th>
                <th>Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($doctor = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($doctor['name']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['specialization']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['qualifications']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['branch']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['availability']); ?></td>
                        <td>
                            <?php if (!empty($doctor['picture'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($doctor['picture']); ?>" class="doctor-picture" alt="Doctor's Image">
                            <?php else: ?>
                                No Picture
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="update_doctor.php?id=<?php echo $doctor['id']; ?>">Update</a> | 
                            <a href="?delete=<?php echo $doctor['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No doctors found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin-dashboard.php" class="back-btn">Back</a>
</body>
</html>
