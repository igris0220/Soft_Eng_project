<?php
// Optional: Start session if needed
session_start();

// Optional: Include header or navigation
include 'header.php'; // make sure you have a separate header file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Ball Games Equipment</title>
    <link rel="stylesheet" href="style.css"> <!-- your CSS file -->
    <style>
        .about-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .about-container h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .about-container p {
            color: #555;
            line-height: 1.8;
        }
    </style>
</head>
<body>

<div class="about-container">
    <h1>About Us</h1>
    <p>
        Welcome to <strong>Ball Games Equipment</strong> – your trusted source for high-quality sports gear.
        Our mission is to provide players, teams, and enthusiasts with the best equipment for basketball, volleyball, football, and more.
    </p>
    <p>
        Established in 2020, our shop has grown with the passion of athletes and the support of our loyal customers.
        We pride ourselves on quality, affordability, and excellent customer service.
    </p>
    <p>
        Whether you're a beginner or a pro, we've got the gear you need to perform at your best. From durable balls to professional-grade nets and accessories, we ensure every product meets your standards.
    </p>
    <p>
        Thank you for choosing us to be part of your sporting journey!
    </p>
</div>

<?php
// Optional: Include footer
include 'footer.php';
?>

</body>
</html>
