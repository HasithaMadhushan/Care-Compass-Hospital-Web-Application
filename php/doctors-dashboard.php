<?php
include 'config.php';


// Fetch all doctors
$query = "SELECT * FROM doctors";
if (isset($_GET['search']) || isset($_GET['specialization']) || isset($_GET['branch'])) {
    $conditions = [];
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $conditions[] = "name LIKE '%$search%'";
    }
    if (!empty($_GET['specialization'])) {
        $specialization = $conn->real_escape_string($_GET['specialization']);
        $conditions[] = "specialization LIKE '%$specialization%'";
    }
    if (!empty($_GET['branch'])) {
        $branch = $conn->real_escape_string($_GET['branch']);
        $conditions[] = "branch = '$branch'";
    }
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
}

$result = $conn->query($query);
if (!$result) {
    die("Error fetching doctors: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #00a9b4;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input, .search-form select {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form button {
            padding: 8px 16px;
            background-color: #005cbf;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #004494;
        }
        .doctor-card {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .doctor-card h3 {
            margin: 0;
            color: #333;
        }
        .doctor-card p {
            margin: 5px 0;
            color: #555;
        }
        .doctor-card img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin-right: 10px;
        }
        .doctor-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .doctor-card a:hover {
            background-color: #28a745;
        }
        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
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
    <h1>Doctors Dashboard</h1>

    <form class="search-form" method="GET">
        <input type="text" name="search" placeholder="Search by name...">
        <input type="text" name="specialization" placeholder="Search by specialization...">
        <select name="branch">
            <option value="">Select Branch</option>
            <option value="Kandy" <?php echo (isset($_GET['branch']) && $_GET['branch'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
            <option value="Colombo" <?php echo (isset($_GET['branch']) && $_GET['branch'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
            <option value="Kurunegala" <?php echo (isset($_GET['branch']) && $_GET['branch'] == 'Kurunegala') ? 'selected' : ''; ?>>Kurunegala</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <div class="doctor-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($doctor = $result->fetch_assoc()): ?>
                <div class="doctor-card">
                    <!-- Show Doctor's Image -->
                    <?php if (!empty($doctor['picture'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($doctor['picture']); ?>" alt="Doctor's Picture">
                    <?php else: ?>
                        <img src="uploads/default.jpg" alt="Default Picture"> <!-- Default image if not uploaded -->
                    <?php endif; ?>

                    <h3><?php echo $doctor['name']; ?></h3>
                    <p><strong>Specialization:</strong> <?php echo $doctor['specialization']; ?></p>
                    <p><strong>Qualifications:</strong> <?php echo $doctor['qualifications']; ?></p>
                    <p><strong>Branch:</strong> <?php echo $doctor['branch']; ?></p>
                    <p><strong>Availability:</strong> <?php echo $doctor['availability']; ?></p>
                    <a href="book-appointment.php?doctor_id=<?php echo $doctor['id']; ?>">Book Appointment</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No doctors found matching your search criteria.</p>
        <?php endif; ?>
        <section class="back-home">
            <a href="../index.php" class="back-btn">Back to Home</a>
        </section>
    </div>
</body>
</html>
