<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Care Compass Hospitals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #00a9b4;
            padding: 20px 0;
            text-align: center;
            color: white;
        }
        header h1 {
            margin: 0;
            font-size: 2em;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 30px 0;
        }
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .contact-form h2 {
            color: #005cbf;
            margin-bottom: 20px;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .contact-form button {
            padding: 10px 20px;
            background-color: #005cbf;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .contact-form button:hover {
            background-color: #004494;
        }
        .location {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .location h3 {
            color: #005cbf;
        }
        .location p {
            margin: 5px 0;
            color: #555;
        }
        .map {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 8px;
        }
        .feedback-message {
            padding: 15px;
            margin: 15px 0;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
            opacity: 1;
            transition: opacity 1s ease-out;
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
                <h1>Care Compass Hospitals</h1>
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
    <!-- Main Content -->
    <div class="container">
        <!-- Feedback Message -->
        <?php
        if (isset($_SESSION['feedback'])) {
            echo '<div class="feedback-message" id="feedback-message">' . $_SESSION['feedback'] . '</div>';
            unset($_SESSION['feedback']); // Clear the message after displaying it
        }
        ?>

        <div class="contact-form">
            <h2>Contact Us</h2>
            <form method="POST" action="send-message.php">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Your Name" required>
                
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Your Email" required>
                
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="6" placeholder="Your Message" required></textarea>
                
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Locations -->
        <div class="location">
            <h3>Our Locations</h3>
            <p><strong>Kandy</strong><br>123 Kandy Road, Kandy<br>Phone: 011-789-1234</p>
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193292.6222763667!2d80.4080102!3d7.2902169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25648f2fd758d%3A0x88dbcb5d6cf3acff!2sKandy!5e0!3m2!1sen!2slk!4v1662213932740!5m2!1sen!2slk&markers=color:red%7Clabel:%E2%9C%94%7C7.2902169,80.4080102" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="location">
            <h3>Colombo</h3>
            <p><strong>Colombo Branch</strong><br>456 Colombo Road, Colombo<br>Phone: 011-223-1234</p>
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d195532.8488468247!2d79.9739372!3d6.9270722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259a6d1a54955%3A0xd71e3ed2255efea9!2sColombo!5e0!3m2!1sen!2slk!4v1662213968457!5m2!1sen!2slk&markers=color:red%7Clabel:%E2%9C%94%7C6.9270722,79.9739372" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="location">
            <h3>Kurunegala</h3>
            <p><strong>Kurunegala Branch</strong><br>789 Kurunegala Road, Kurunegala<br>Phone: 011-456-7890</p>
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d194106.3620154636!2d80.3570152!3d7.4809784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae249d041c7f22d%3A0xf87276374f10dbed!2sKurunegala!5e0!3m2!1sen!2slk!4v1662214022479!5m2!1sen!2slk&markers=color:red%7Clabel:%E2%9C%94%7C7.4809784,80.3570152" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <script>
        // If feedback message is displayed, set a timeout to fade it out after a few seconds
        window.onload = function() {
            var feedbackMessage = document.getElementById("feedback-message");
            if (feedbackMessage) {
                setTimeout(function() {
                    feedbackMessage.style.opacity = 0;
                }, 3000); // Set the delay to 3 seconds (3000ms)
            }
        }
    </script>
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
