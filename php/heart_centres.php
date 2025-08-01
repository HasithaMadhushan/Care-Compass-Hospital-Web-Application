<?php
session_start();  // Start the session to check if the user is logged in
include 'config.php'; // Include the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Centres - Care Compass Hospitals</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9f0f5; /* Light background color */
        }
        
        .container {
            width: 80%;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            border-radius: 12px; /* Rounded corners */
        }
        h2 {
            color: #00a9b4;
            font-size: 24px;
            margin-top: 20px;
            text-align: center;
        }
        .container p {
            font-size: 16px;
            color: #666;
            line-height: 1.8;
            text-align: justify;
        }
        ul {
            list-style: none;
            padding-left: 20px;
        }
        .container li {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            padding-left: 20px;
            position: relative;
        }
        .container li::before {
            content: '✔';
            position: absolute;
            left: 0;
            color: #005cbf;
        }
    
    
        .appointment-btn {
            padding: 10px 20px;
            background-color: #218838;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer; /* Green button for appointments */
        }
        .appointment-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
        }
        header {
            background-color: #00a9b4; /* Blue-green header */
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 24px;
            border-bottom: 4px solid #0097a7;
        }

        header h1 {
            margin: 0;
        }
        /*from here*/
h1, h2, h3 {
    margin: 0;
}

a {
    text-decoration: none;
    color: #333;
}

.top-bar li {
    display: inline;
    margin-right: 20px;
}

/* Header Section */
header {
    background-color: #00a9b4;
    color: #fff;
    padding: 10px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
}

nav a {
    color: #fff;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color:rgb(8, 9, 9);
}

/

/* Dropdown Menu */
nav .dropdown {
    position: relative;
    display: inline-block;
}

nav .dropdown .dropbtn {
    background-color: #00a9b4;
    color: #fff;
    padding: 10px 20px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    text-align: center;
}

nav .dropdown:hover .dropbtn {
    background-color: #00a9b4;
}

nav .dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    pointer-events: auto;
}

nav .dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-size: 1rem;
}

nav .dropdown-content a:hover {
    background-color: #00a9b4;
}

nav .dropdown:hover .dropdown-content {
    display: block;
}

/* Submenu */
nav .submenu {
    display: none;
    position: absolute;
    top: 0;
    left: 100%;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
}

nav .dropdown-submenu:hover .submenu {
    display: block;
}
/* Responsive dropdown */
@media (max-width: 768px) {
    nav .dropdown .dropbtn {
        padding: 10px;
    }

    nav .dropdown-content {
        position: relative;
        min-width: 100%;
        box-shadow: none;
    }

    nav .submenu {
        left: 0;
        top: 0;
    }
}

    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="logo">
                <h1>Heart Centres</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Services</a>
                        <!-- First level dropdown content -->
                        <div class="dropdown-content">
                            <!-- Centres of Excellence (Sub-category) -->
                            <div class="dropdown-submenu">
                                <a href="#">Centres of Excellence</a>
                                <ul class="submenu">
                                    <li><a href="accident_and_emergency.php">Accident and Emergency</a></li>
                                    <li><a href="heart_centres.php">Heart Centres</a></li>
                                    <li><a href="brain_centres.php">Brain and Spine Centre</a></li>
                                </ul>
                            </div>
                            <!-- Health & Wellness (Sub-category) -->
                            <div class="dropdown-submenu">
                                <a href="#">Health & Wellness</a>
                                <ul class="submenu">
                                    <li><a href="amazing_care.php">Amazing Care</a></li>
                                    <li><a href="diabetes_centre.php">Diabetes Care</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>   
                    <li><a href="about-us.php">About Us</a></li>
                    <li><a href="contact-us.php">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h1>Care Compass Heart Centres</h1>

        <p>The Care Compass Heart Centres provide cutting-edge diagnostics, treatment, and rehabilitation for cardiovascular diseases. With branches in Colombo, Kandy, and Kurunegala, we ensure high-quality clinical care for all our patients.</p>

        <h2>Our Services & Treatments</h2>
        <p>Our Cardiac Care Programme offers a wide range of services including:</p>
        <ul>
            <li>24/7 Cardiac Catheterization and Angiography</li>
            <li>Advanced diagnostic tools including echocardiograms, treadmill ECGs, and more</li>
            <li>Comprehensive interventional procedures such as angioplasty, stent placement, and heart surgeries</li>
            <li>Cardiac rehabilitation, lifestyle management, and post-surgical care</li>
            <li>Support for heart conditions during pregnancy</li>
        </ul>

        <h2>Conditions We Treat</h2>
        <p>At Care Compass Heart Centres, we treat patients for a range of cardiovascular diseases, including:</p>
        <ul>
            <li>Acute Coronary Syndrome</li>
            <li>Myocardial Infarction (Heart Attack)</li>
            <li>Endocarditis (Heart Infection)</li>
            <li>Cardiomyopathy (Heart Muscle Disease)</li>
            <li>Aortic Aneurysm and Aortic Dissection</li>
            <li>Pericardial Effusion (Fluid Around the Heart)</li>
            <li>Heart conditions in pregnancy</li>
            <li>Syncope (Fainting) due to heart rhythm problems</li>
        </ul>

        <h2>Why Choose Care Compass Heart Centres?</h2>
        <p>We are dedicated to providing exceptional care for patients with heart conditions. Here’s why you should choose Care Compass Heart Centres:</p>
        <ul>
            <li>State-of-the-art Cardiac Catheterization Laboratories and Operating Theatres</li>
            <li>Highly skilled team of cardiologists, cardiac surgeons, and nursing staff</li>
            <li>Post-surgical rehabilitation programs, including physical and occupational therapy</li>
            <li>Specialized cardiac care for vulnerable populations including pregnant women and children</li>
            <li>24/7 availability of cardiologists and cardiac surgeons for emergencies</li>
            <li>Comprehensive patient support, including lifestyle modification guidance for long-term health</li>
        </ul>

        <h2>Our Achievements</h2>
        <p>Care Compass Heart Centres are proud to offer the following facilities and achievements:</p>
        <ul>
            <li>20 Coronary Care Beds</li>
            <li>40 Cardiac Ward Rooms</li>
            <li>4 Cardiac Operating Theatres</li>
            <li>10 Cardiac Channeling Rooms</li>
            <li>16 Cardio–Thoracic Surgical Intensive Care Beds</li>
            <li>3 State-of-the-art Cardiac Catheterization Laboratories</li>
            <li>2 Fully Computerized Heart Care Centres</li>
        </ul>

        <h2>About Heart Disease</h2>
        <p>Heart disease can present in many forms, and recognizing the symptoms early is crucial. Some symptoms include:</p>
        <ul>
            <li>Chest pain, discomfort, or pressure</li>
            <li>Shortness of breath or difficulty breathing</li>
            <li>Fainting or dizziness due to arrhythmias</li>
            <li>Fatigue or weakness, especially with physical activity</li>
        </ul>
        <p>If you experience any of these symptoms, seek immediate medical attention to prevent further damage to the heart.</p>

        <!-- Appointment Button (Checks if user is logged in before redirecting) -->
        <div class="back-home">
            <a href="doctors-dashboard.php" class="appointment-btn">Make an Appointment</a>
        </div>

    </div>
<!-- Footer -->
<footer style="background-color: #0097A7; color: white; padding: 20px 0; text-align: center;">
    <div class="footer-content" style="display: flex; flex-wrap: wrap; justify-content: space-around; align-items: center; max-width: 1200px; margin: auto;">
        
        <!-- Quick Links -->
        <div class="quick-links" style="flex: 1; min-width: 200px;">
            <h4 style="margin-bottom: 10px;">Quick Links</h4>
            <ul style="list-style: none; padding: 0;">
                <li><a href="#" style="color: white; text-decoration: none;">Privacy Policy</a></li>
                <li><a href="contact-us.php" style="color: white; text-decoration: none;">Contact Us</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="contact-info" style="flex: 1; min-width: 250px;">
            <p>Contact Us: <a href="tel:0117891234" style="color: white; text-decoration: none;">011-789-1234</a> | 
               <a href="mailto:info@carecompass.com" style="color: white; text-decoration: none;">info@carecompass.com</a></p>
        </div>

        <!-- Social Links -->
        <div class="social-links" style="flex: 1; min-width: 200px;font-size: 30px;">
            <h4 style="margin-bottom: 10px;">Follow Us</h4>
            <a href="https://www.facebook.com" style="margin-right: 10px;"><img src="../assets/fb.png" alt="Facebook" width="50"></a>
            <a href="https://www.twitter.com" style="margin-right: 10px;"><img src="../assets/twitter.png" alt="Twitter" width="50"></a>
            <a href="https://www.instagram.com"><img src="../assets/instagram.png" alt="Instagram" width="50"></a>
        </div>
    </div>

    <!-- Copyright -->
    <div style="margin-top: 10px; font-size: 14px;color: #333;">
        &copy; 2025 Care Compass Hospitals. All Rights Reserved.
    </div>
</footer>
</body>
</html>
