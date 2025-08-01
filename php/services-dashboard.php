<!DOCTYPE html>
<html lang="en">
<head>
    <style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
}

header {
    background-color: #005cbf;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header nav a {
    color: white;
    text-decoration: none;
    margin-right: 15px;
}

header nav a.active {
    font-weight: bold;
    text-decoration: underline;
}

.services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
}

.service-card {
    background-color: white;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: calc(33.333% - 20px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.service-card h3 {
    color: #005cbf;
}

.service-card .cta-button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #f57c00;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}

.service-card .cta-button:hover {
    background-color: #d65a00;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Care Compass Hospitals - Services Dashboard</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="services-dashboard.php" class="active">Services</a>
            <a href="doctors-dashboard.php">Doctors</a>
            <a href="patient-dashboard.php">Patient Dashboard</a>
            <a href="contact-us.php">Contact Us</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Our Services</h2>
            <form method="GET" class="service-filters">
                <input type="text" name="search" placeholder="Search services by name...">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="Outpatient">Outpatient Services</option>
                    <option value="Diagnostics">Diagnostic Tests</option>
                    <option value="Specialized">Specialized Treatments</option>
                </select>
                <button type="submit">Filter</button>
            </form>

            <div class="services-list">
                <?php
                include 'config.php';

                // Filter services
                $query = "SELECT * FROM services";
                if (!empty($_GET['search']) || !empty($_GET['category'])) {
                    $conditions = [];
                    if (!empty($_GET['search'])) {
                        $search = $conn->real_escape_string($_GET['search']);
                        $conditions[] = "name LIKE '%$search%'";
                    }
                    if (!empty($_GET['category'])) {
                        $category = $conn->real_escape_string($_GET['category']);
                        $conditions[] = "category = '$category'";
                    }
                    if ($conditions) {
                        $query .= " WHERE " . implode(" AND ", $conditions);
                    }
                }

                $result = $conn->query($query);
                if ($result->num_rows > 0):
                    while ($service = $result->fetch_assoc()): ?>
                        <div class="service-card">
                            <h3><?php echo $service['name']; ?></h3>
                            <p><?php echo $service['description']; ?></p>
                            <p><strong>Category:</strong> <?php echo $service['category']; ?></p>
                            <a href="book-service.php?service_id=<?php echo $service['id']; ?>" class="cta-button">Book Now</a>
                        </div>
                    <?php endwhile;
                else: ?>
                    <p>No services found matching your criteria.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Care Compass Hospitals. All rights reserved.</p>
    </footer>
</body>
</html>
