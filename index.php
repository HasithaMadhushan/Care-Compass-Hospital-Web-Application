<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Home</title>
    
    <style>
/* General Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h1, h2, h3 {
    margin: 0;
}

a {
    text-decoration: none;
    color: #333;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
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

nav ul {
    display: flex;
    gap: 20px;
    
}

nav a {
    color: #fff;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color: rgb(8, 9, 9);
}

/* Hero Slider */
.hero-slider h2 {
    font-size: 4rem; /* Larger text for the heading */
    font-weight: bold;
    text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.6);
    color: #fff; /* White text color */
    margin: 0;
    padding: 0;
    text-transform: uppercase;
    letter-spacing: 3px;
    z-index: 1; /* Ensure heading stays above other elements */
    line-height: 1.2; /* Slightly tighter line height for better compactness */
}

.hero-slider p {
    font-size: 1.2rem; /* Smaller text for the paragraph */
    color: rgba(255, 255, 255, 0.8); /* Lighter text color */
    margin-top: 10px; /* Ensures there's a little gap between the heading and paragraph */
    font-weight: 300; /* Lighter font weight for the paragraph */
    line-height: 1.6;
    padding: 0 10px;
    transition: opacity 0.3s ease-in-out;
    z-index: 1; /* Ensure the paragraph stays above other elements */
    text-align: center; /* Center-align the paragraph text */
}

/* Optional hover effect for paragraph */
.hero-slider p:hover {
    opacity: 1; /* Slight opacity change on hover for emphasis */
}

.hero-slider {
    position: relative;
    overflow: hidden;
    height: 400px;
    background-color: #333;
}

.slides {
    display: flex;
    transition: transform 0.5s ease;
}

.slide {
    width: 100%;
    height: 400px;
    background-size: cover;
    background-position: center;
    text-align: center;
    color: white;
    display: none;
    justify-content: center;
    align-items: center;
    padding: 20px;
    position: relative;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

/* Ensure the first slide is visible */
.slide:first-child {
    display: flex;
    opacity: 1;
}

.slide h2 {
    font-size: 3rem;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.6);
}

.slide p {
    font-size: 1.2rem;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}

.controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
}

button {
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    font-size: 2rem;
    border: none;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

/* What's New Section */
.whats-new {
    padding: 40px;
    background-color: #fff;
}

.news-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.news-item {
    width: 30%;
}

.news-item img {
    width: 100%;
    height: auto;
}

.news-item h3 {
    font-size: 1.5rem;
    margin-top: 10px;
}

.news-item a {
    color: #3498db;
}
/* FAQ Section Styling */
#faq {
    padding: 40px;
    background-color: #fff;
    text-align: center;
}

#faq h2 {
    font-size: 2.5rem;
    color: #005cbf;
    margin-bottom: 20px;
}

.faq-list {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    text-align: left;
}

.faq-item {
    margin-bottom: 15px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.faq-question {
    width: 100%;
    padding: 15px;
    background-color: #00a9b4;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.2rem;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.3s;
}

.faq-question:hover {
    background-color: #008b8f;
}

.faq-answer {
    padding: 10px 15px;
    background-color: #f4f4f4;
    border-radius: 5px;
    margin-top: 10px;
    font-size: 1rem;
    color: #555;
}

/* Search Bar Styling */
#faqSearch {
    font-size: 1.1rem;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ddd;
}
/* Testimonials Section */
.testimonials {
    background-color: #ecf0f1;
    padding: 40px;
    text-align: center;
}

.testimonial-slider {
    display: flex;
    gap: 40px;
    justify-content: center;
}

.testimonial {
    max-width: 300px;
    margin: 0 10px;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.testimonial p {
    font-style: italic;
    color: #7f8c8d;
}

.testimonial span {
    font-weight: bold;
    color: #2c3e50;
}

/* Scroll to Top Button */
#scrollTopBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#scrollTopBtn:hover {
    background-color: #2980b9;
}

/* Responsive Design */
@media (max-width: 768px) {
    .news-container {
        flex-direction: column;
    }

    .testimonial-slider {
        flex-direction: column;
    }

    footer .footer-content {
        flex-direction: column;
        text-align: center;
    }

    .hero-slider {
        height: 300px;
    }

    .slide h2 {
        font-size: 2.5rem;
    }

    .slide p {
        font-size: 1rem;
    }

    /* Mobile Navigation */
    nav ul {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .top-bar {
        padding: 10px;
    }
}

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
    color:rgb(15, 15, 15);
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
/* Feedback Section */
.feedback-section {
    background-color: #f4f4f9;
    padding: 40px 20px;
    text-align: center;
}

.feedback-section h2 {
    color: #005cbf;
    font-size: 24px;
}

.feedback-section p {
    margin-bottom: 20px;
    color: #555;
}

.feedback-form {
    display: inline-block;
    max-width: 600px;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.feedback-form label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: bold;
}

.feedback-form input, .feedback-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
}

.feedback-form button {
    background-color: #005cbf;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.feedback-form button:hover {
    background-color: #004494;
}

/* Success and Error messages */
.success, .error {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    font-weight: bold;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 2px solid #f5c6cb;
}
/* Feedback message styles */
.success {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
    padding: 15px;
    margin-top: 20px;
    border-radius: 5px;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 2px solid #f5c6cb;
    padding: 15px;
    margin-top: 20px;
    border-radius: 5px;
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
    <!-- Header Section -->
    <header>
        <div class="top-bar">
            <div class="logo">
                <h1>Care Compass Hospitals</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Services</a>
                        <!-- First level dropdown content -->
                        <div class="dropdown-content">
                            <!-- Centres of Excellence (Sub-category) -->
                            <div class="dropdown-submenu">
                                <a href="#">Centres of Excellence</a>
                                <ul class="submenu">
                                    <li><a href="php/accident_and_emergency.php">Accident and Emergency</a></li>
                                    <li><a href="php/heart_centres.php">Heart Centres</a></li>
                                    <li><a href="php/brain_centres.php">Brain and Spine Centre</a></li>
                                </ul>
                            </div>
                            <!-- Health & Wellness (Sub-category) -->
                            <div class="dropdown-submenu">
                                <a href="#">Health & Wellness</a>
                                <ul class="submenu">
                                    <li><a href="php/amazing_care.php">Amazing Care</a></li>
                                    <li><a href="php/diabetes_centre.php">Diabetes Care</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li><a href="php/patient-dashboard.php">Patient Dashboard</a></li>     
                    <!-- Conditionally show Doctors link if patient is logged in -->
                    <?php if (isset($_SESSION['patient_id'])): ?>
                        <li><a href="php/doctors-dashboard.php">Doctors</a></li>
                    <?php endif; ?>
                    <li><a href="php/about-us.php">About Us</a></li>
                    <li><a href="php/contact-us.php">Contact Us</a></li>
                    <!-- Check if the user is logged in -->
                    <?php if (isset($_SESSION['patient_id'])): ?>
                        <!-- If patient is logged in -->
                        <li><a href="php/logout.php">Logout</a></li>
                    <?php else: ?>
                        <!-- If user is not logged in, show Login and Register options -->
                        <li><a href="php/register.php">Register</a></li>
                        <li><a href="php/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Slider -->
    <section class="hero-slider">
        <div class="slides">
            <div class="slide" style="background-image: url('assets/hero1.jpeg');">
                <h2>Compassionate Care, Always</h2>
            </div>
            <div class="slide" style="background-image: url('assets/hero2.jpeg');">
                <h2>Advanced Treatments</h2>
            </div>
            <div class="slide" style="background-image: url('assets/hero3.jpeg');">
                <h2>Your Health, Our Priority</h2>
            </div>
        </div>
        <div class="controls">
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
    </section>

    <!-- What's New Section -->
    <!-- What's New Section -->
<section class="whats-new">
    <h2>What's New</h2>
    <div class="news-container">
        <!-- Fake Story 1 -->
        <div class="news-item">
            <img src="assets/news1.webp" alt="News 1" loading="lazy">
            <h3>Endometrial Cancer Treatment</h3>
            <p>Raquel Ferreroya found hope with Care Compass Hospitals' groundbreaking endometrial cancer treatments...</p>
            <a href="php/news-detail.php?id=1">Read More</a>
        </div>
        
        <!-- Fake Story 2 -->
        <div class="news-item">
            <img src="assets/news2.webp" alt="News 2" loading="lazy">
            <h3>Family Coping with Cancer</h3>
            <p>Care Compass therapists helped Emerson cope with his father’s cancer treatment journey, offering counseling...</p>
            <a href="php/news-detail.php?id=2">Read More</a>
        </div>
        
        <!-- Fake Story 3 -->
        <div class="news-item">
            <img src="assets/news3.webp" alt="News 3" loading="lazy">
            <h3>Gene Therapy Success</h3>
            <p>Our Gene Therapy Center offers cutting-edge treatments, restoring vision for many patients who were once blind...</p>
            <a href="php/news-detail.php?id=3">Read More</a>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Patients Say</h2>
        <div class="testimonial-slider">
            <div class="testimonial">
                <p>"The doctors at Care Compass were so compassionate and professional. Highly recommend!"</p>
                <span>- John D.</span>
            </div>
            <div class="testimonial">
                <p>"State-of-the-art facilities and great staff. My surgery was a success thanks to them."</p>
                <span>- Sarah M.</span>
            </div>
            <div class="testimonial">
                <p>"Exceptional care and attention to detail. They truly go above and beyond."</p>
                <span>- Alex R.</span>
            </div>
        </div>
    </section>

    <!-- Live FAQ Search -->
    <section id="faq">
    <h2>Frequently Asked Questions</h2>


    <div class="faq-list" id="faqList">
        <?php
        include 'php/config.php'; // Include your database configuration file
        $query = "SELECT * FROM faqs";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0): 
            while ($faq = $result->fetch_assoc()): ?>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)"><?php echo $faq['question']; ?></button>
                    <div class="faq-answer" style="display: none;">
                        <p><?php echo $faq['answer']; ?></p>
                    </div>
                </div>
            <?php endwhile;
        else: ?>
            <p>No FAQs available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>
</section>
    <!-- Feedback Section (Only visible when patient is logged in) -->
    <?php if (isset($_SESSION['patient_id'])): ?>
        <section class="feedback-section">
            <h2>We value your feedback</h2>

            <?php if (!empty($success_message)): ?>
                <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <form action="php/submit_feedback.php" method="POST" class="feedback-form">
                <button type="submit">Submit Feedback</button>
            </form>
        </section>
    <?php endif; ?>

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
            <a href="https://www.facebook.com" style="margin-right: 10px;"><img src="assets/fb.png" alt="Facebook" width="50"></a>
            <a href="https://www.twitter.com" style="margin-right: 10px;"><img src="assets/twitter.png" alt="Twitter" width="50"></a>
            <a href="https://www.instagram.com"><img src="assets/instagram.png" alt="Instagram" width="50"></a>
        </div>
    </div>

    <!-- Copyright -->
    <div style="margin-top: 10px; font-size: 14px;color: #333;">
        &copy; 2025 Care Compass Hospitals. All Rights Reserved.
    </div>
</footer>
<script>
    // Testimonial Slider (Automatic slide change)
let currentTestimonial = 0;
const testimonials = document.querySelectorAll('.testimonial');

// Function to show the testimonial
function showTestimonial() {
    testimonials.forEach((testimonial, index) => {
        testimonial.style.display = 'none'; // Hide all testimonials
    });
    testimonials[currentTestimonial].style.display = 'block'; // Show current testimonial
}

// Set interval to automatically change testimonial every 5 seconds
setInterval(() => {
    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
    showTestimonial();
}, 5000);

// Initial testimonial display
showTestimonial();

    // Get all slides and buttons
    const slides = document.querySelectorAll('.slide');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    let currentSlideIndex = 0;

    // Show the slide at the given index
    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.style.display = 'none'; // Hide all slides initially
            slide.style.opacity = '0'; // Make sure opacity is 0
        });

        // Show the current slide
        slides[index].style.display = 'flex'; // Make the current slide visible
        slides[index].style.opacity = '1'; // Set opacity to 1 to fade in
    }

    // Move to the next slide
    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % slides.length; // Loop back to the first slide
        showSlide(currentSlideIndex);
    }

    // Move to the previous slide
    function prevSlide() {
        currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length; // Loop back to the last slide
        showSlide(currentSlideIndex);
    }

    // Show the initial slide
    showSlide(currentSlideIndex);

    // Attach event listeners to buttons
    prevButton.addEventListener('click', prevSlide);
    nextButton.addEventListener('click', nextSlide);

    // Optionally, you can add an automatic slide transition (every 5 seconds, for example)
    setInterval(nextSlide, 5000);
    // Accordion functionality
function toggleAnswer(button) {
    const answer = button.nextElementSibling;
    if (answer.style.display === "none" || answer.style.display === "") {
        answer.style.display = "block";
    } else {
        answer.style.display = "none";
    }
}

// FAQ search functionality
function filterFAQ() {
    const searchQuery = document.getElementById("faqSearch").value.toLowerCase();
    const faqItems = document.querySelectorAll(".faq-item");

    faqItems.forEach(item => {
        const question = item.querySelector(".faq-question").innerText.toLowerCase();
        if (question.includes(searchQuery)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}
</script>

</body>
</html>
