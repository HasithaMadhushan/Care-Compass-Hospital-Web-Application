<?php
include 'config.php';

$query = "SELECT * FROM medical_treatments";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Treatments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Medical Treatments</h1>
    <div class="treatments">
        <?php while ($treatment = $result->fetch_assoc()): ?>
            <div class="treatment-card">
                <img src="uploads/<?php echo $treatment['image']; ?>" alt="<?php echo $treatment['name']; ?>">
                <h2><?php echo $treatment['name']; ?></h2>
                <p><?php echo $treatment['description']; ?></p>
                <p><strong>Department:</strong> <?php echo $treatment['department']; ?></p>
                <a href="find-doctor.php?treatment=<?php echo $treatment['id']; ?>" class="btn">Find a Doctor</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
