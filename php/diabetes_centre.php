<?php 
    session_start();
    include('config.php'); // Include the database configuration file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diabetes Centre - Care Compass Hospitals</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb; /* Light background color */
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
            color:rgb(11, 11, 11); /* Blue header for sections */
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
    <header>
        <div class="top-bar">
            <div class="logo">
                <h1>Diabetes Centre</h1>
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

        <div class="section">
            <h2>Diabetes Awareness Month: Small Changes, Big Impact</h2>
            <p>Did You Know? Lifestyle choices like high-carb diets, lack of exercise, and stress can increase your risk of diabetes. But awareness and simple steps can help you lead a healthier life! The Diabetes Centre at Care Compass Hospitals is dedicated to raising awareness and helping manage diabetes with tailored care plans. Get in touch with us to start your journey toward better health.</p>
        </div>

        <div class="section">
            <h2>Know the Risk Factors</h2>
            <ul>
                <li>
                    <h3>Overweight?</h3>
                    <p>Start small with 15 minutes of daily walking.</p>
                </li>
                <li>
                    <h3>Family history?</h3>
                    <p>Get your blood sugar tested regularly.</p>
                </li>
                <li>
                    <h3>Unhealthy eating?</h3>
                    <p>Add more vegetables to your rice & curry.</p>
                </li>
            </ul>
            <p>Take action today! Early detection is the key to a long, healthy life.</p>
        </div>

        <div class="section">
            <h2>Healthy Living Tips for Sri Lankans with Diabetes</h2>
            <ul>
                <li>Swap white rice with red rice or quinoa for better blood sugar control.</li>
                <li>Practice mindfulness or yoga to reduce stress levels.</li>
                <li>Include Sri Lankan superfoods like gotukola and moringa in your meals.</li>
                <li>Avoid sugary drinks—opt for herbal teas like coriander or karapincha.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Prevent Diabetic Foot Complications</h2>
            <ul>
                <li>Wash and Dry Daily: Keep your feet clean and dry, especially between toes.</li>
                <li>Inspect Your Feet: Check for redness, cuts, or swelling.</li>
                <li>Wear Comfortable Shoes: Avoid walking barefoot, even at home.</li>
            </ul>
            <p>Take care of your feet, your foundation for a healthy lifestyle.</p>
        </div>

        <div class="section">
            <h2>Spot the Symptoms Early</h2>
            <ul>
                <li>Excessive thirst?</li>
                <li>Frequent urination?</li>
                <li>Blurred vision or sudden weight loss?</li>
            </ul>
            <p>These could be signs of diabetes. Consult your doctor immediately for a check-up.</p>
        </div>

        <div class="section">
            <h2>Let's Manage Diabetes Together</h2>
            <p>At Care Compass Hospitals, we offer the following services to help you manage your diabetes:</p>
            <ul>
                <li>Regular screening for diabetic complications.</li>
                <li>Personalized nutrition advice to suit your Sri Lankan palate.</li>
                <li>Foot care and vision screening.</li>
            </ul>
            <p>Our Diabetes Care Centre is ready to assist you with the tools and support you need to manage diabetes and live a healthier life.</p>
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
