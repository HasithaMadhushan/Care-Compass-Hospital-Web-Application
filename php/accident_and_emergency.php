<?php
session_start();
include 'config.php'; // Include the database configuration file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accident and Emergency - Care Compass Hospitals</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9f0f5; /* Light background color */
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

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 40px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            color:rgb(12, 12, 12);
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .service-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .service-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            flex: 1 1 calc(33.33% - 20px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            text-align: center;
            margin-bottom: 20px;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .service-card h3 {
            color: #00a9b4;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .service-card p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        ul {
            padding-left: 20px;
            list-style-type: square;
            margin-top: 20px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            margin-top: 30px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .success, .error {
            font-size: 14px;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .service-card {
                flex: 1 1 100%;
            }
        }
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
                <h1>Accident & Emergency</h1>
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
        <!-- Display Success or Error Message -->
        <?php if (!empty($admin_message)): ?>
            <div class="success"><?php echo htmlspecialchars($admin_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <h2>Welcome to Care Compass Hospitals' Emergency Unit</h2>
        <p>The Emergency Department (ED), also known as Accident & Emergency (A&E) or Emergency Treatment Unit (ETU), specializes in the acute care of patients who require immediate treatment. Our 24-hour Emergency Treatment Unit (ETU) is equipped with state-of-the-art technology and staffed by experienced professionals to handle any type of medical or surgical emergency.</p>

        <h3>How We Operate</h3>
        <p>Our ETU is equipped to handle trauma cases, acute conditions, cardiac procedures, stroke treatments, and more. The department follows a triage system where patients are assessed and referred to the appropriate care station.</p>

        <h3>Our Treatment and Services</h3>
        <div class="service-list">
            <div class="service-card">
                <h3>Triage System</h3>
                <p>Our triage system ensures that the most critical cases are treated first. We prioritize patients based on the severity of their conditions.</p>
            </div>
            <div class="service-card">
                <h3>24-Hour Service</h3>
                <p>We provide round-the-clock emergency services to ensure that urgent medical situations are addressed without delay.</p>
            </div>
            <div class="service-card">
                <h3>Doctor Availability</h3>
                <p>At least 2 doctors are available at any given time to provide immediate care for emergencies.</p>
            </div>
            <div class="service-card">
                <h3>Trauma Management</h3>
                <p>We offer specialized trauma care with multi-parameter monitoring and full resuscitation facilities for all types of injuries.</p>
            </div>
            <div class="service-card">
                <h3>Cardiac and Stroke Care</h3>
                <p>Our ETU is equipped with catheterization labs to provide immediate treatment for heart attacks and strokes.</p>
            </div>
            <div class="service-card">
                <h3>Acute Care</h3>
                <p>We are prepared to treat a broad range of acute medical emergencies, excluding burns and psychological conditions.</p>
            </div>
        </div>

        <h3>What Sets Us Apart</h3>
        <ul class="">
            <li>Level 1 Emergency Department (as per Ministry of Health standards in Sri Lanka)</li>
            <li>Consultant-led, 24-hour emergency services</li>
            <li>Full resuscitation facilities and trauma care</li>
            <li>Multi-parameter monitoring for all critical patients</li>
        </ul>
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
