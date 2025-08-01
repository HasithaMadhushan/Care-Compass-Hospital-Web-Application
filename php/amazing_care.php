<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Amazing Care Membership</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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

        .container h1 {
            color: rgb(7, 7, 7); /* Consistent blue for headers */
            text-align: center;
        }
        h2{color: #00a9b4;text-align: center;}
        h2{color: #00a9b4;text-align: center;}
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
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Grid layout */
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
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .section h3 {
            font-size: 20px;
            color: #00a9b4;
            margin-bottom: 10px;
            
        }

        .section p, .section ul {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #dc3545;
        }

        .contact {
            text-align: center;
            background-color: #005cbf;
            color: white;
            padding: 25px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            background-color: #6c757d;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #5a6268;
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
                <h1>Amazing Care</h1>
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
    <h1>Care Compass Amazing Care Membership</h1>

    <div class="section">
        <h2>Comprehensive Patient-Centered Care</h2>
        <p>Care Compass Amazing Care is a 24/7 Mobile Emergency and Home Health Care Service offering safe, empathetic, high-quality healthcare services catering to a wide range of medical needs.</p>
    </div>

    <div class="section">
        <h2>Our Services</h2>
        <ul>
            <li>Emergency Ambulance Service</li>
            <li>Doctor & Nurse Home Visits</li>
            <li>Inter-Hospital Transfers</li>
            <li>Emergency Medical Services</li>
            <li>Advanced Life Support & Basic Life Support</li>
            <li>Cardiac & Trauma Care</li>
            <li>Oxygen Administration & Pain Management</li>
            <li>Medical Transfers, Laboratory Tests, ECG</li>
            <li>IV Medication, Saline, Enema Procedure</li>
            <li>Catheterization & Tube Insertion</li>
            <li>Suture & Clip Removal</li>
            <li>Mobile Physiotherapy, Post-Surgical & Geriatric Care</li>
            <li>Corporate Medical Services</li>
            <li>Emergency First Aid & Medical Event Coverage</li>
            <li>Coordination for Hospital Admissions & Consultations</li>
        </ul>
    </div>

    <div class="section">
    <h2>Membership Benefits</h2>
    <ul>
        <li class="highlight">24/7 Emergency Ambulance Service</li>
        <li>Nationwide patient transfers, including inter-hospital and home-to-hospital transfers.</li>
        <li>Transfers for medical investigations, imaging, dialysis, etc.</li>
        <li>International Patient Transfers.</li>
        <li><span class="highlight">10% Discount on:</span>
            <ul>
                <li>Transport Charges [Home Visits with/Without Doctors]</li>
                <li>Room Charges</li>
                <li>Laboratory & Imaging Services (X-ray, CT, MRI, US Scan, etc.)</li>
                <li>Dental & Physiotherapy Procedures</li>
            </ul>
        </li>
        <li><span class="highlight">5% Discount on Pharmacy Bills</span> (Minimum bill over Rs.1,000, excluding IV and supplementary drugs).</li>
        <li><span class="highlight">Free Eye & Dental Checkup</span> (At Care Compass Colombo Hospital).</li>
        <li><span class="highlight">Special Health Check Package</span> (At Care Compass Colombo Hospital).</li>
    </ul>
    <p style="text-align:center; font-style: italic;">Conditions Apply</p>
</div>

    <div class="section">
        <h2>Membership Packages</h2>
        <ul>
            <li><strong>Senior Citizens Couple</strong></li>
            <li><strong>Family 1</strong> (Husband, Wife & 3 Children)</li>
            <li><strong>Family 2</strong> (Any 5 Family Members)</li>
            <li><strong>Individual Plan</strong></li>
        </ul>
    </div>

    <div class="section">
        <h2>Why Choose Our Services?</h2>
        <ul>
            <li>Qualified doctors and ICU-trained nurses** with advanced life support certification.</li>
            <li>Ambulance drivers are trained in patient care and emergency handling.</li>
            <li>We work closely with all hospitals, laboratories, and healthcare providers.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Home Health Care</h2>
        <p>Home health care is medical assistance provided by doctors and nurses at home. It ensures continuity of care after hospitalization. Services include diagnosis, treatment, follow-ups, and referrals to specialists when necessary.</p>
    </div>

    <div class="section">
        <h2>Our Ambulance Services</h2>
        <p>We prioritize your well-being with Basic Life Support (BLS) and Advanced Life Support (ALS) ambulances.</p>

        <h3>Basic Life Support (BLS)</h3>
        <p>Equipped for non-emergency patient transport, including hospital visits, dialysis, and minor treatments.</p>

        <h3>Advanced Life Support (ALS)</h3>
        <p>For critical emergency cases, including ICU transfers and advanced emergency treatment en route.</p>

        <h3>Ambulance Equipment:</h3>
        <ul>
            <li>Defibrillator, Monitor, Portable Ventilator</li>
            <li>Emergency Drugs, Oxygen Therapy</li>
            <li>Advanced Airway Management & Suction Kit</li>
            <li>Spinal Collars, Spine Board, Inflatable Splints</li>
            <li>Syringe Pumps, Nebulizer, ECG</li>
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
