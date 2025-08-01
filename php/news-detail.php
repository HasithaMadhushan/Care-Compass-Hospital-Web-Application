<?php
// Simulate getting the news ID from the URL
if (isset($_GET['id'])) {
    $news_id = $_GET['id'];
} else {
    $news_id = 1; // Default to the first story
}

// Fake data for news stories
$news_data = [
    1 => [
        'title' => 'Endometrial Cancer Treatment',
        'content' => 'Raquel Ferreroya found hope with Care Compass Hospitals\' groundbreaking endometrial cancer treatments. The hospital offers innovative therapies and personalized care that have helped thousands of patients.',
        'image' => '../assets/news4.jpg',
        'date' => 'January 15, 2025',
        'author' => 'Dr. Emily Hayes',
        'additional_info' => 'Endometrial cancer treatments have advanced significantly in recent years. Care Compass Hospitals have provided state-of-the-art care, helping numerous patients regain their health.',
        'more_info' => 'For more information, contact our specialists in women\'s health at Care Compass Hospitals.'
    ],
    2 => [
        'title' => 'Family Coping with Cancer',
        'content' => 'Care Compass therapists helped Emerson cope with his father’s cancer treatment journey, offering counseling and emotional support throughout the difficult process.',
        'image' => '../assets/news5.jpg',
        'date' => 'February 5, 2025',
        'author' => 'Therapist Laura Green',
        'additional_info' => 'Our team of therapists specializes in offering emotional and psychological support to families dealing with cancer treatment. Care Compass strives to make sure both patients and families feel supported during these challenging times.',
        'more_info' => 'To speak with one of our counselors, contact Care Compass\'s support team for a consultation.'
    ],
    3 => [
        'title' => 'Gene Therapy Success',
        'content' => 'Our Gene Therapy Center offers cutting-edge treatments, restoring vision for many patients who were once blind. The groundbreaking treatment has shown great success in clinical trials.',
        'image' => '../assets/news6.jpg',
        'date' => 'March 22, 2025',
        'author' => 'Dr. John Smith',
        'additional_info' => 'Gene therapy is paving the way for revolutionary medical treatments, especially for vision restoration. At Care Compass Hospitals, we have been at the forefront of these advancements, providing hope for many patients.',
        'more_info' => 'To learn more about the gene therapy treatments offered at Care Compass Hospitals, contact our specialists.'
    ],
    // Add more fake news stories here...
];

// Get the current news story based on the ID
$current_news = $news_data[$news_id] ?? $news_data[1]; // Default to the first story if the ID is not valid
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_news['title']; ?> - Care Compass Hospitals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .news-detail {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .news-detail h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .news-detail img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .news-detail p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }

        .news-detail .author {
            font-style: italic;
            color: #777;
        }

        .news-detail .date {
            color: #888;
            margin-bottom: 15px;
        }

        .news-detail .additional-info {
            margin-top: 30px;
            font-size: 1rem;
            background-color: #f7f7f7;
            padding: 15px;
            border-left: 5px solid #00a9b4;
            border-radius: 8px;
        }

        .news-detail .more-info {
            background-color: #e0f7fa;
            padding: 10px;
            margin-top: 20px;
            border-left: 5px solid #00a9b4;
            color: #00796b;
            font-size: 1rem;
            border-radius: 8px;
        }

        .back-link {
            margin-top: 30px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #00a9b4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #008c91;
        }
    </style>
</head>
<body>
    <!-- News Detail Section -->
    <section class="news-detail">
        <h1><?php echo $current_news['title']; ?></h1>
        <div class="date"><?php echo $current_news['date']; ?></div>
        <div class="author">By <?php echo $current_news['author']; ?></div>
        <img src="<?php echo $current_news['image']; ?>" alt="<?php echo $current_news['title']; ?>" loading="lazy">
        <p><?php echo $current_news['content']; ?></p>

        <div class="additional-info">
            <h3>Additional Information:</h3>
            <p><?php echo $current_news['additional_info']; ?></p>
        </div>

        <div class="more-info">
            <h3>Want to know more?</h3>
            <p><?php echo $current_news['more_info']; ?></p>
        </div>

        <a href="../index.php" class="back-link">Back to News</a>
    </section>
</body>
</html>
