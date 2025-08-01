<?php
// Include header and database connection here
session_start();
include 'config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Brain and Spine Centre</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
        }
        
        header {
            background-color: #00a9b4; /* Blue-green header */
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            border-bottom: 4px solid #0097a7;
        }

        h2 {
            color:rgb(7, 7, 7); /* Blue header for sections */
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            margin-bottom: 40px;
        }

        /* Section for cards with hover effect */
        .section ul {
            list-style-type: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Auto grid layout */
            gap: 20px;
        }

        .section li {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .section li:hover {
            transform: translateY(-5px); /* Slight lift effect */
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
        }

        .section li h3 {
            font-size: 20px;
            color: #00a9b4;
            margin-bottom: 10px;
        }

        .section li p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            text-align: center;
            font-weight: bold;
            margin: 30px auto;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        /* Success/Error Message */
        .success, .error {
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Section Header Styling */
        .section-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 15px;
            }

            .section ul {
                grid-template-columns: 1fr; /* Single column for small screens */
            }
        }
        h1, h2, h3 {
    margin: 0;
}

a {
    text-decoration: none;
    color: #333;
}

li {
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
<!-- Header -->
<header>
        <div class="top-bar">
            <div class="logo">
                <h1>Brain and Spine Centre</h1>
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
        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="section services-section">
            <h2>Our Services & Treatments</h2>
            <ul>
                <li>
                    <h3>Management of Head and Spinal injuries</h3>
                    <p>We provide comprehensive treatment for various head and spinal injuries, ensuring the best recovery outcomes.</p>
                </li>
                <li>
                    <h3>Comprehensive Back and Neck Pain Management</h3>
                    <p>We use both non-invasive and surgical treatments to manage chronic back and neck pain effectively.</p>
                </li>
                <li>
                    <h3>Surgery for Disc Prolapse and Cervical Disc Replacement</h3>
                    <p>Advanced surgical techniques to treat disc prolapse and provide cervical disc replacements for better mobility.</p>
                </li>
                <li>
                    <h3>Degenerative Spine Management</h3>
                    <p>Specialized care for managing degenerative spine conditions, including tailored physical therapy and surgery.</p>
                </li>
                <li>
                    <h3>Correction and Fusion for Spondylolisthesis</h3>
                    <p>Our surgical approaches correct spinal misalignment and prevent further damage to spinal structures.</p>
                </li>
                <!-- Add more items as necessary -->
            </ul>
        </div>

        <div class="section achievements-section">
            <h2>Our Achievements</h2>
            <ul>
                <li>
                    <h3>13,259 Neurosurgical Procedures</h3>
                    <p>Performed since 2004, marking our legacy of excellence in brain and spine surgery.</p>
                </li>
                <li>
                    <h3>1st Private Hospital to Perform Epilepsy Surgery</h3>
                    <p>Leading the way with groundbreaking procedures in the private healthcare sector of Sri Lanka.</p>
                </li>
                <li>
                    <h3>Neuro Navigation & Intraoperative Monitoring</h3>
                    <p>First to acquire these advanced technologies to improve surgical precision and patient safety.</p>
                </li>
                <!-- Add more items as necessary -->
            </ul>
        </div>

        <div class="section facilities-section">
            <h2>Our Facilities</h2>
            <ul>
                <li>
                    <h3>Neurosurgery Ward with 40 Patient Rooms</h3>
                    <p>Our dedicated ward ensures optimal care for patients undergoing neurosurgery.</p>
                </li>
                <li>
                    <h3>Neurosurgical ICU with 12 Separate Rooms</h3>
                    <p>State-of-the-art ICU with monitoring systems to provide critical care for neurosurgery patients.</p>
                </li>
                <li>
                    <h3>Neurosurgery Theatre Complex</h3>
                    <p>Equipped with three advanced theatres for safe and efficient neurosurgical procedures.</p>
                </li>
                <li>
                    <h3>Comprehensive Neuro Imaging Centre</h3>
                    <p>Complete diagnostic imaging facilities including MRI, CT scans, and angiography.</p>
                </li>
                <!-- Add more items as necessary -->
            </ul>
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
