<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Include your database connection

$success_message = '';
$error_message = '';

// Get the doctor ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $doctor_id = intval($_GET['id']);

    // Fetch the doctor's details from the database
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
    $stmt->close();

    if (!$doctor) {
        $error_message = "Doctor not found.";
    }

    // Handle form submission (update doctor information)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_doctor') {
        $name = trim($_POST['name']);
        $specialization = trim($_POST['specialization']);
        $qualifications = trim($_POST['qualifications']);
        $branch = trim($_POST['branch']);
        $availability = trim($_POST['availability']);

        // Handle file upload
        $image_name = $_FILES['picture']['name'];
        $image_tmp_name = $_FILES['picture']['tmp_name'];
        $image_error = $_FILES['picture']['error'];
        $image_size = $_FILES['picture']['size'];

        // If a new image is uploaded
        if ($image_error === 0) {
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($image_ext), $allowed_ext) && $image_size <= 5000000) {
                // Generate a unique name for the image
                $new_image_name = uniqid('', true) . "." . $image_ext;
                $image_destination = 'uploads/' . $new_image_name; // Save the image in the 'uploads' directory
                
                move_uploaded_file($image_tmp_name, $image_destination); // Move the uploaded file

                $doctor_picture = $new_image_name; // Save the image name in the database
            } else {
                $error_message = "Invalid image file or file size exceeds limit.";
            }
        } else {
            $doctor_picture = $doctor['picture']; // If no new image is uploaded, keep the old image
        }

        if (!empty($name) && !empty($specialization) && !empty($qualifications) && !empty($branch) && !empty($availability)) {
            // Update the doctor's details in the database
            $stmt = $conn->prepare("UPDATE doctors SET name = ?, specialization = ?, qualifications = ?, branch = ?, availability = ?, picture = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $name, $specialization, $qualifications, $branch, $availability, $doctor_picture, $doctor_id);

            if ($stmt->execute()) {
                $success_message = "Doctor updated successfully.";
            } else {
                $error_message = "Error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_message = "All fields are required.";
        }
    }
} else {
    header('Location: manage_doctors.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Doctor</title>
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
        .image-preview {
            max-width: 200px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Update Doctor</h1>

    <?php if (!empty($success_message)): ?>
        <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Update Doctor Form -->
    <form method="POST" enctype="multipart/form-data" class="form-container">
        <input type="hidden" name="action" value="update_doctor">

        <label for="name">Doctor's Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>

        <label for="specialization">Specialization:</label>
        <input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($doctor['specialization']); ?>" required>

        <label for="qualifications">Qualifications:</label>
        <input type="text" id="qualifications" name="qualifications" value="<?php echo htmlspecialchars($doctor['qualifications']); ?>" required>

        <label for="branch">Branch:</label>
        <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($doctor['branch']); ?>" required>

        <label for="availability">Availability:</label>
        <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($doctor['availability']); ?>" required>

        <label for="picture">Upload Doctor's Picture:</label>
        <input type="file" id="picture" name="picture" accept="image/*">
        
        <?php if (!empty($doctor['picture'])): ?>
            <div class="image-preview">
                <img src="uploads/<?php echo htmlspecialchars($doctor['picture']); ?>" alt="Doctor's Image" />
            </div>
        <?php endif; ?>

        <button type="submit">Update Doctor</button>
    </form>

    <a href="insert-doctors.php" class="back-btn">Back</a>
</body>
</html>
